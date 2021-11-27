<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Sentinel;
use Illuminate\Support\Str;

class TermController extends Controller
{
    public function __construct()
    {
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreateTermButton']);
    }

    public function _addCreateTermButton()
    {
        $screen = current_screen();
        $params = Route::current()->parameters();
        foreach ($params as $key => $param) {
            if ($key !== 'id' && $key !== 'page') {
                $screen .= '/' . $param;
            }
        }
        $folder = $this->getFolder();

        if ($screen == 'get-terms/home-amenity') {
            echo view("dashboard.screens.{$folder}.services.home.quick-add-home-amenity")->render();
        } elseif ($screen == 'get-terms/home-facilities') {
            echo view("dashboard.screens.{$folder}.services.home.quick-add-home-facilities")->render();
        } elseif ($screen == 'get-terms/home-type') {
            echo view("dashboard.screens.{$folder}.services.home.quick-add-home-type")->render();
        } elseif ($screen == 'get-terms/experience-type') {
            echo view("dashboard.screens.{$folder}.services.experience.quick-add-experience-type")->render();
        } elseif ($screen == 'get-terms/experience-languages') {
            echo view("dashboard.screens.{$folder}.services.experience.quick-add-experience-languages")->render();
        } elseif ($screen == 'get-terms/experience-inclusions') {
            echo view("dashboard.screens.{$folder}.services.experience.quick-add-experience-inclusions")->render();
        } elseif ($screen == 'get-terms/experience-exclusions') {
            echo view("dashboard.screens.{$folder}.services.experience.quick-add-experience-exclusions")->render();
        } elseif ($screen == 'get-terms/post-category') {
            echo view('dashboard.components.quick-add-post-category')->render();
        } elseif ($screen == 'get-terms/post-tag') {
            echo view('dashboard.components.quick-add-post-tag')->render();
        }elseif ($screen == 'get-terms/car-type') {
            echo view("dashboard.screens.{$folder}.services.car.quick-add-car-type")->render();
        }elseif ($screen == 'get-terms/car-equipment') {
            echo view("dashboard.screens.{$folder}.services.car.quick-add-car-equipment")->render();
        }elseif ($screen == 'get-terms/car-feature') {
            echo view("dashboard.screens.{$folder}.services.car.quick-add-car-feature")->render();
        }elseif ($screen == 'get-terms/home-distance') {
            echo view("dashboard.screens.{$folder}.services.home.quick-add-home-distance")->render();
        }elseif ($screen == 'get-terms/home-advanced') {
            echo view("dashboard.screens.{$folder}.services.home.quick-add-home-advanced")->render();
        }
    }

    public function _addNewTerm(Request $request)
    {
        $termName = set_translate('term_name');
        $termDescription = set_translate('term_description');
        $termImage = request()->get('term_image');
        $termIcon = request()->get('term_icon');
        $termPrice = request()->get('term_price');
        $taxonomyName = request()->get('taxonomy_name', '');
        $current = request()->get('currentNum');
        $term_select = request()->get('term_select');

        $value = array();
        for($i = 1; $i <= (int)($current); $i++){
            $val = request()->get('sub_name_add_'.$i);
            if(!empty($val)){
                array_push($value, $val);
            }
        }

        $value = json_encode($value);

        if (empty(trim($termName))) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('the Term Name is required')
            ], true);
        }
        $taxonomy = new Taxonomy();
        $taxObject = $taxonomy->getByName($taxonomyName);

        if (!$taxObject) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Taxonomy is not available')
            ], true);
        }

        $data = [
            'term_title' => $termName,
            'term_name' => Str::slug(get_translate($termName)),
            'term_description' => $termDescription,
            'term_image' => $termImage,
            'term_icon' => $termIcon,
            'taxonomy_id' => $taxObject->taxonomy_id,
            'term_price' => (float)$termPrice,
            'created_at' => time(),
            'author' => get_current_user_id(),
            'term_select' => $value,
        ];
        if($taxObject->taxonomy_id == 14){
            $data['term_select'] = $term_select;
        }

        $term = new Term();

        $created = $term->createTerm($data);

        if ($created) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Created Successfully'),
                'reload' => true
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not create new term')
            ], true);
        }
    }

    public function _deleteTermItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $deleted = $term->deleteTerm($termID);
        if ($deleted) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Deleted successfully'),
                'reload' => true
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not delete this term')
            ], true);
        }
    }

    public function _updateTermItem(Request $request)
    {
        $termID = request()->get('term_id');
        $termEncrypt = request()->get('term_encrypt');
        $termName = set_translate('term_name');
        $termDescription = set_translate('term_description');
        $termImage = request()->get('term_image');
        $termIcon = request()->get('term_icon');
        $termPrice = request()->get('term_price');
        $current = request()->get('currentNum_update');
        $term_select = request()->get('term_select');
        $term_type = request()->get('term_type');

        $value = array();
        for($i = 1; $i <= (int)($current); $i++){
            $val = request()->get('sub_name_update_'.$i);
            if(!empty($val)){
                array_push($value, $val);
            }
        }

        $value = json_encode($value);

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $data = [
            'term_title' => $termName,
            'term_name' => Str::slug(get_translate($termName)),
            'term_description' => $termDescription,
            'term_image' => $termImage,
            'term_icon' => $termIcon,
            'term_price' => (float)$termPrice,
            'term_select' => $value
        ];

        if(!empty($term_type)) {
            $data['term_select'] = $term_select;
        }

        $termUpdated = $term->updateTerm($data, $termID);

        if (is_null($termUpdated)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not update this term')
            ], true);
        } else {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('This term is updated')
            ], true);
        }
    }

    public function _getTermItem(Request $request, $name)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');
        $service_type = explode('-', $name)[0];
        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $html = view("dashboard.screens.{$this->getFolder()}.services.{$service_type}.{$name}-form", ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);

    }

    public function _getPostCategoryItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This category is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This category is invalid')
            ], true);
        }

        $html = view('dashboard.components.post-category-form', ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);

    }

    public function _getPostTagItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This tag is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This tag is invalid')
            ], true);
        }

        $html = view('dashboard.components.post-tag-form', ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);

    }

    public function _getHomeAmenityItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $html = view("dashboard.screens.{$this->getFolder()}.services.home.home-amenity-form", ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);
    }

    public function _getHomeFacilitiesItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $html = view("dashboard.screens.{$this->getFolder()}.services.home.home-facilities-form", ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);
    }

    public function _getHomeDistanceItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $html = view("dashboard.screens.{$this->getFolder()}.services.home.home-distance-form", ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);
    }

    public function _getHomeAdvancedItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $html = view("dashboard.screens.{$this->getFolder()}.services.home.home-advanced-form", ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);
    }

    public function _getExperienceTypeItem(Request $request)
    {
        $termID = request()->get('termID');
        $termEncrypt = request()->get('termEncrypt');

        if (!hh_compare_encrypt($termID, $termEncrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $term = new Term();
        $termObject = $term->getById($termID);
        if (is_null($termObject)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This term is invalid')
            ], true);
        }

        $html = view("dashboard.screens.{$this->getFolder()}.services.experience.experience-type-form", ['termObject' => $termObject])->render();

        $this->sendJson([
            'status' => 1,
            'html' => $html
        ], true);

    }

    public function _getTerms(Request $request, $type = 'home-type', $page = 1)
    {
        $folder = $this->getFolder();

        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'term_id');
        $order = request()->get('order', 'desc');
        $service_type = explode('-', $type)[0];
        $data = [
            'tax' => $type,
            'search' => $search,
            'page' => $page,
            'orderby' => $orderBy,
            'order' => $order,
        ];


        if (!is_admin()) {
            $data['author'] = get_current_user_id();
        }
        $allTerms = $this->getAllTerms(
            $data
        );

        return view("dashboard.screens.{$this->getFolder()}.services.{$service_type}.{$type}", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'allTerms' => $allTerms]);
    }

    public function getAllTerms($data = [])
    {
        $term = new Term();
        return $term->getAllTerms($data);
    }

}
