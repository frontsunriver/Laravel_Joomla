<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarPrice;
use App\Models\HomeAvailability;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\HomePrice;
use App\Models\ExperiencePrice;
use Illuminate\Validation\Rules\In;

class CustomPriceController extends Controller
{
    public function _changeStatusCustomPriceItem(Request $request)
    {
        $id = request()->get('priceID');
        $encrypt = request()->get('priceEncrypt');
        $status = request()->get('val');
        $post_type = request()->get('postType');

        if (!hh_compare_encrypt($id, $encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This range is not available')
            ], true);
        }

        if($post_type == 'home') {
	        $price_model = new HomePrice();
        }elseif($post_type == 'experience'){
	        $price_model = new ExperiencePrice();
        }else{
	        $price_model = new CarPrice();
        }
        $has = $price_model->getByID($id);

        if (!$has) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This range is not available')
            ], true);
        }

        if ($post_type == 'home') {
            $avai = new HomeAvailability();

            $hasAvai = $avai->getItem($has->home_id, $has->start_time, $has->end_time);
            if ($hasAvai && is_object($hasAvai)) {
                if ($status == 'on') {
                    $avai->deleteAvailability($has->home_id, $has->start_time, $has->end_time);
                }
            } else {
                if ($status != 'on') {
                    $avai->createAvailability([
                        'home_id' => $has->home_id,
                        'start_time' => $has->start_time,
                        'start_date' => $has->start_time,
                        'end_time' => $has->end_time,
                        'end_date' => $has->end_time,
                        'total_minutes' => 1440
                    ]);
                }
            }
        }
        $data = [
            'available' => $status ? 'on' : 'off'
        ];
        $updated = $price_model->updatePrice($data, $id);

        if (!is_null($updated)) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Updated successfully')
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not update this range')
            ], true);
        }
    }

    public function _deleteCustomPriceItem(Request $request)
    {
        $priceID = request()->get('priceID');
        $postID = request()->get('postID');
        $postType = request()->get('postType');
        if (!$priceID || !$postID || !in_array($postType, ['home', 'experience', 'car'])) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not save data. This service is invalid')
            ], true);
        }
        $func = '_deletePrice' . ucfirst($postType);
        $delete = $this->$func($priceID);

        $post = get_post($postID, $postType);
        $booking_type = isset($post->booking_type) ? $post->booking_type : '';
        $class = 'App\\Models\\' . ucfirst($postType) . 'Price';
        $priceModel = new $class();
        if ($delete) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Successfully'),
                'html' => view("dashboard.components.services.{$postType}.custom_price", ['custom_price' => $priceModel->getAllPrices($postID), 'booking_type' => $booking_type, 'post_id' => $postID])->render(),
            ], true);
        }
        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Have error when delete custom price')
        ], true);
    }

    private function _deletePriceHome($priceID)
    {
        $priceObject = new HomePrice();
        $has = $priceObject->getByID($priceID);
        if ($has) {
            $avai = new HomeAvailability();
            $avai->deleteAvailability($has->home_id, $has->start_time, $has->end_time);
        }
        return $priceObject->deletePrice($priceID);
    }

    private function _deletePriceExperience($priceID)
    {
        $priceObject = new ExperiencePrice();
        return $priceObject->deletePrice($priceID);
    }

    private function _deletePriceCar($priceID)
    {
        $priceObject = new CarPrice();
        return $priceObject->deletePrice($priceID);
    }

    public function _addNewCustomPrice(Request $request)
    {
        $post_type = request()->get('post_type', 'home');
        $responsive = [];

        if ($post_type == 'home') {
            $responsive = $this->_addNewCustomPriceHome();
        }

        if ($post_type == 'experience') {
            $responsive = $this->_addNewCustomPriceExperience();
        }

	    if ($post_type == 'car') {
		    $responsive = $this->_addNewCustomPriceCar();
	    }

        return $this->sendJson($responsive);

    }

    private function _convertDayOfWeek()
    {
        $type = request()->get('type_of_bulk', '');

        $day_of_week = request()->get('days_of_week_bulk', []);
        $day_of_month = request()->get('days_of_month_bulk', []);
        $data_week = $this->_day_off_week();
        $convert_data_week = [];

        if ($type == 'days_of_week') {
            $day_of_week = (array)$day_of_week;
            foreach ($day_of_week as $key => $day) {
                $convert_data_week[] = $data_week[$day];
            }
        } elseif ($type == 'days_of_month') {
            $day_of_month = (array)$day_of_month;
            $convert_data_week = $day_of_month;
        }
        return $convert_data_week;
    }

    private function get_group_alone()
    {
        $group = $alone = [];

        $convert_data_week = $this->_convertDayOfWeek();
        $full_stack = false;
        for ($i = 0; $i < count($convert_data_week) - 1; $i++) {
            $group_tmp = [$convert_data_week[$i]];
            for ($j = $i + 1; $j < count($convert_data_week); $j++) {
                if ($convert_data_week[$j] == $group_tmp[count($group_tmp) - 1] + 1) {
                    $group_tmp[] = $convert_data_week[$j];
                    if (count($group_tmp) == count($convert_data_week) - $i) {
                        $full_stack = true;
                    }
                } else {
                    $i = $j - 1;
                    break;
                }
            }
            if (count($group_tmp) >= 2) {
                $group[] = $group_tmp;
            }
            if ($full_stack) {
                break;
            }
        }
        $alone = $convert_data_week;
        foreach ($convert_data_week as $key => $day) {
            foreach ($group as $item) {
                if (in_array($day, $item)) {
                    unset($alone[$key]);
                }
            }
        }

        return [$group, $alone];
    }

    private function _validation($type = 'home')
    {
        $param = request()->get('type_of_bulk', '');
        $start_date = request()->get('start_date');
        $end_date = request()->get('end_date');
        $month = request()->get('month_bulk');
        $year = request()->get('year_bulk');
        $price = request()->get('price_bulk');
        $postID = request()->get('post_id_bulk');
        $start_date_discount = request()->get('start_date_discount');
        $end_date_discount = request()->get('end_date_discount');
        $first_minute = request()->get('first_minute');
        $last_minute = request()->get('last_minute');
        if (!$postID) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not save data. This service is invalid')
            ];
        }

        if ($type == 'home') {
            if (!is_numeric($price) || (float)$price < 0) {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('The price is incorrect')
                ];
            }
        } elseif ($type == 'experience') {
            $booking_type = request()->get('booking_type', 'date_time');
            if($booking_type!= 'package'){
                $adult_price = (float)request()->get('adult_price');
                $child_price = (float)request()->get('child_price');
                $infant_price = (float)request()->get('infant_price');
                if ((float)$adult_price < 0 || (float)$child_price < 0 || (float)$infant_price < 0) {
                    return [
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('The price is incorrect')
                    ];
                }
            }
            if($booking_type == 'date_time'){
                $times = request()->get('time_of_day');
                if(empty($times)){
                    return [
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Select time to set availabile')
                    ];
                }
            }
        }elseif($type == 'car'){
	        if (!is_numeric($price) || (float)$price < 0) {
		        return [
			        'status' => 0,
			        'title' => __('System Alert'),
			        'message' => __('The price is incorrect')
		        ];
	        }
        }

        if($param == 'days_of_custom') {
            if (empty($start_date) || empty($end_date)){
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Please insert the start or end date')
                ];
            }
        } else if ($param == 'days_of_discount'){
            if (empty($start_date_discount) || empty($end_date_discount)){
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Please insert the start or end date')
                ];
            }
            if ($first_minute == 'off' && last_minute == 'off') {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Please select the First Minute or Last Minute')
                ];
            }
        } else{
            if (empty($month) || !is_array($month)) {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Month is incorrect')
                ];
            }

            if (empty($year) || !is_array($year)) {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Year is incorrect')
                ];
            }
        }

        

        return [
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Valid!')
        ];
    }

    private function _day_off_week()
    {
        return [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7
        ];
    }

	public function _addNewCustomPriceCar()
	{
		$validation = $this->_validation('car');
		if ($validation['status'] == 0) {
			$this->sendJson($validation, true);
		}
		$type = request()->get('type_of_bulk', '');

		$month = request()->get('month_bulk');
		$year = request()->get('year_bulk');
		$price = request()->get('price_bulk');
		$available = request()->get('available_bulk', 'on');
		$postID = request()->get('post_id_bulk');

		$price = (float)$price;

		$priceModel = new CarPrice();

		$data_week = $this->_day_off_week();
		list($group, $alone) = $this->get_group_alone();

		if (!empty($group) || !empty($alone)) {
			$data_week = array_flip($data_week);
			if (!empty($group)) {
				foreach ($year as $_year) {
					foreach ($month as $_month) {
						if ($type == 'days_of_week') {
							foreach ($group as $group_item) {
								$start = strtotime('first ' . $data_week[$group_item[0]] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
								$_start = strtotime('last ' . $data_week[$group_item[0]] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
								if ($start) {
									for ($i = $start; $i <= $_start; $i = strtotime('+1 week', $i)) {
										$start_item = $i;
										$end_item = strtotime('first ' . $data_week[$group_item[count($group_item) - 1]], $i);
										if (date('Ym', $start_item) != date('Ym', $end_item)) {
											$last_date = strtotime(date('Y-m-t', $i));
											if ($last_date < $end_item) {
												$end_item = $last_date;
											}
										}
										$priceModel->_savePrice($postID, $start_item, $end_item, $price, $available);
									}
								}
							}
						} elseif ($type == 'days_of_month') {
							foreach ($group as $group_item) {
								$start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $group_item[0]));
								$end = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $group_item[count($group_item) - 1]));
								$last_date = strtotime(date('Y-m-t', $start));
								if ($end > $last_date) {
									$end = $last_date;
								}
								if ($start && $end) {
									$priceModel->_savePrice($postID, $start, $end, $price, $available);
								}
							}

						}
					}
				}
			}
			if (!empty($alone)) {
				foreach ($year as $_year) {
					foreach ($month as $_month) {
						if ($type == 'days_of_week') {
							foreach ($alone as $day) {
								$start = strtotime('first ' . $data_week[$day] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
								$_start = strtotime('last ' . $data_week[$day] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
								if ($start) {
									for ($i = $start; $i <= $_start; $i = strtotime('+1 week', $i)) {
										$priceModel->_savePrice($postID, $i, $i, $price, $available);
									}
								}
							}
						} elseif ($type == 'days_of_month') {
							foreach ($alone as $day) {
								$start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $day));
								if ($start) {
									$priceModel->_savePrice($postID, $start, $start, $price, $available);
								}
							}
						}
					}
				}
			}

		} else {
			foreach ($year as $_year) {
				foreach ($month as $_month) {
					$start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-01');
					$end = strtotime(date($_year . '-' . sprintf('%02d', $_month) . '-t'));
					$priceModel->_savePrice($postID, $start, $end, $price, $available);
				}
			}
		}

		$this->sendJson([
			'status' => 1,
			'title' => __('System Alert'),
			'message' => __('Added Successfully'),
			'html' => view("dashboard.components.services.car.custom_price", ['custom_price' => $priceModel->getAllPrices($postID), 'post_id' => $postID])->render(),
		], true);

	}

    public function _addNewCustomPriceExperience()
    {
        $validation = $this->_validation('experience');
        if ($validation['status'] == 0) {
            $this->sendJson($validation, true);
        }

        $times = request()->get('time_of_day');
        $type = request()->get('type_of_bulk', '');
        $month = request()->get('month_bulk');
        $year = request()->get('year_bulk');
        $booking_type = request()->get('booking_type', 'date_time');
        $postID = request()->get('post_id_bulk');

        $adult_price = (float)null2empty(request()->get('adult_price'));
        $child_price = (float)null2empty(request()->get('child_price'));
        $infant_price = (float)null2empty(request()->get('infant_price'));

        $max_people = (float)request()->get('max_people', 0);

        if ($max_people < 0) {
            $max_people = -1;
        }

        $priceModel = new ExperiencePrice();

        $data_week = $this->_day_off_week();
        $convert_data_week = $this->_convertDayOfWeek();
        if ($booking_type == 'date_time') {
            if (!empty($times)) {
                if (!empty($convert_data_week)) {
                    foreach ($year as $_year) {
                        foreach ($month as $_month) {
                            foreach ($convert_data_week as $date) {
                                foreach ($times as $time) {
                                    if ($type == 'days_of_week') {
                                        $data_week_revert = array_flip($data_week);
                                        $start = strtotime('first ' . $data_week_revert[$date] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
                                        $end = strtotime('last ' . $data_week_revert[$date] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
                                        if ($start && $end) {
                                            for ($i = $start; $i <= $end; $i = strtotime('+1 week', $i)) {
                                                $start_item = strtotime(date('Y-m-d', $i) . ' ' . $time);
                                                $priceModel->_savePrice($postID, $start_item, $start_item, $adult_price, $child_price, $infant_price, $max_people);
                                            }
                                        }
                                    } elseif ($type == 'days_of_month') {
                                        $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $date) . ' ' . $time);
                                        $end = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $date) . ' ' . $time);
                                        $last_date = strtotime(date('Y-m-t h:i A', $start));
                                        if ($end <= $last_date) {
                                            $priceModel->_savePrice($postID, $start, $end, $adult_price, $child_price, $infant_price, $max_people);
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($year as $_year) {
                        foreach ($month as $_month) {
                            $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-01');
                            $end = strtotime(date($_year . '-' . sprintf('%02d', $_month) . '-t'));
                            for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
                                foreach ($times as $time) {
                                    $date = strtotime(date('Y-m-d ', $i). $time);
                                    $priceModel->_savePrice($postID, $date, $date, $adult_price, $child_price, $infant_price, $max_people);
                                }
                            }
                        }
                    }
                }
            }
        } else{
            if (!empty($convert_data_week)) {
                foreach ($year as $_year) {
                    foreach ($month as $_month) {
                        foreach ($convert_data_week as $date) {
                            if ($type == 'days_of_week') {
                                $data_week_revert = array_flip($data_week);

                                $start = strtotime('first ' . $data_week_revert[$date] . ' of ' . $_year. '-' . sprintf('%02d', $_month));
                                $end = strtotime('last ' . $data_week_revert[$date] . ' of ' . $_year. '-' . sprintf('%02d', $_month));
                                if ($start && $end) {
                                    for ($i = $start; $i <= $end; $i = strtotime('+1 week', $i)) {
                                        $priceModel->_savePrice($postID, $i, $i, $adult_price, $child_price, $infant_price, $max_people);
                                    }
                                }
                            } elseif ($type == 'days_of_month') {
                                $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $date));
                                $end = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $date));
                                $last_date = strtotime(date('Y-m-t', $start));
                                if ($end <= $last_date) {
                                    $priceModel->_savePrice($postID, $start, $end, $adult_price, $child_price, $infant_price, $max_people);
                                }
                            }
                        }
                    }
                }
            } else {
                foreach ($year as $_year) {
                    foreach ($month as $_month) {
                        $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-01');
                        $end = strtotime(date($_year . '-' . sprintf('%02d', $_month) . '-t'));
                        for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
                            $date = strtotime(date('Y-m-d', $i));
                            $priceModel->_savePrice($postID, $date, $date, $adult_price, $child_price, $infant_price, $max_people);
                        }
                    }
                }
            }
        }
        $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Added Successfully'),
            'html' => view("dashboard.components.services.experience.custom_price", ['custom_price' => $priceModel->getAllPrices($postID), 'booking_type' => $booking_type, 'post_id' => $postID])->render(),
        ], true);
    }

    public function _addNewCustomPriceHome()
    {
        $validation = $this->_validation('home');
        if ($validation['status'] == 0) {
            $this->sendJson($validation, true);
        }
        $type = request()->get('type_of_bulk', '');
        $month = request()->get('month_bulk');
        $year = request()->get('year_bulk');
        $price = request()->get('price_bulk');
        $available = request()->get('available_bulk', 'on');
        $postID = request()->get('post_id_bulk');
        $start_date = request()->get('start_date');
        $end_date = request()->get('end_date');
        $start_date_discount = request()->get('start_date_discount');
        $end_date_discount = request()->get('end_date_discount');
        $price_per_night = request()->get('price_per_night');
        $min_stay_date = request()->get('min_stay_date');
        $first_minute = request()->get('first_minute');
        $last_minute = request()->get('last_minute');
        $discount_percent = request()->get('discount_percent');

        $price = (float)$price;
        $price_per_night = (float)$price_per_night;
        $min_stay_date = (int)$min_stay_date;

        $priceModel = new HomePrice();
        $availability_model = new HomeAvailability();

        if($type == 'days_of_custom'){
            $priceModel->_savePricePerNight($postID, $start_date, $end_date, $price_per_night, $available, $min_stay_date);
        } else if($type == 'days_of_discount') {
            $priceModel->_saveSpecialPrice($postID, $start_date_discount, $end_date_discount, $discount_percent, $available, $first_minute, $last_minute);
        } else {
            $data_week = $this->_day_off_week();
            list($group, $alone) = $this->get_group_alone();
    
            if (!empty($group) || !empty($alone)) {
                $data_week = array_flip($data_week);
                if (!empty($group)) {
                    foreach ($year as $_year) {
                        foreach ($month as $_month) {
                            if ($type == 'days_of_week') {
                                foreach ($group as $group_item) {
                                    $start = strtotime('first ' . $data_week[$group_item[0]] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
                                    $_start = strtotime('last ' . $data_week[$group_item[0]] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
                                    if ($start) {
                                        for ($i = $start; $i <= $_start; $i = strtotime('+1 week', $i)) {
                                            $start_item = $i;
                                            $end_item = strtotime('first ' . $data_week[$group_item[count($group_item) - 1]], $i);
                                            if (date('Ym', $start_item) != date('Ym', $end_item)) {
                                                $last_date = strtotime(date('Y-m-t', $i));
                                                if ($last_date < $end_item) {
                                                    $end_item = $last_date;
                                                }
                                            }
                                            $priceModel->_savePrice($postID, $start_item, $end_item, $price, $available);
                                            $availability_model->_saveAvailability($postID, $start_item, $end_item, $available);
    
                                        }
                                    }
                                }
                            } elseif ($type == 'days_of_month') {
                                foreach ($group as $group_item) {
                                    $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $group_item[0]));
                                    $end = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $group_item[count($group_item) - 1]));
                                    $last_date = strtotime(date('Y-m-t', $start));
                                    if ($end > $last_date) {
                                        $end = $last_date;
                                    }
                                    if ($start && $end) {
                                        $priceModel->_savePrice($postID, $start, $end, $price, $available);
                                        $availability_model->_saveAvailability($postID, $start, $end, $available);
    
                                    }
                                }
    
                            }
                        }
                    }
                }
                if (!empty($alone)) {
                    foreach ($year as $_year) {
                        foreach ($month as $_month) {
                            if ($type == 'days_of_week') {
                                foreach ($alone as $day) {
                                    $start = strtotime('first ' . $data_week[$day] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
                                    $_start = strtotime('last ' . $data_week[$day] . ' of ' . $_year . '-' . sprintf('%02d', $_month));
                                    if ($start) {
                                        for ($i = $start; $i <= $_start; $i = strtotime('+1 week', $i)) {
                                            $priceModel->_savePrice($postID, $i, $i, $price, $available);
                                            $availability_model->_saveAvailability($postID, $i, $i, $available);
                                        }
                                    }
                                }
                            } elseif ($type == 'days_of_month') {
                                foreach ($alone as $day) {
                                    $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-' . sprintf('%02d', $day));
                                    if ($start) {
                                        $priceModel->_savePrice($postID, $start, $start, $price, $available);
                                        $availability_model->_saveAvailability($postID, $start, $start, $available);
                                    }
                                }
                            }
    
                        }
                    }
                }
    
            } else {
                foreach ($year as $_year) {
                    foreach ($month as $_month) {
                        $start = strtotime($_year . '-' . sprintf('%02d', $_month) . '-01');
                        $end = strtotime(date($_year . '-' . sprintf('%02d', $_month) . '-t'));
                        $priceModel->_savePrice($postID, $start, $end, $price, $available);
                        $availability_model->_saveAvailability($postID, $start, $end, $available);
                    }
                }
            }
        }
        

        $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Created successfully'),
            'html' => view('dashboard.components.services.home.custom_price', ['custom_price' => $priceModel->getAllPrices($postID)])->render(),
        ], true);

    }

    public static function getAllPrices($post_id, $post_type = 'home')
    {
        if ($post_type == 'home') {
            $price = new HomePrice();
        } else {
            $class = 'App\\Models\\' . ucfirst($post_type) . 'Price';
            $price = new $class();
        }

        return $price->getAllPrices($post_id);
    }
}
