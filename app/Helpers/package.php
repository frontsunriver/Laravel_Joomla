<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/22/2019
 * Time: 9:16 PM
 */
use App\Models\Package;
use App\Models\PackageOrder;

function get_number_of_package(){
	$packageModel = new Package();
	return $packageModel->getTotaPackge();
}

function get_package_by_user($user_id = ''){
	if (empty($user_id)) {
		$user_id = get_current_user_id();
	}
	$packageOrder = new PackageOrder();
	$package = $packageOrder->getItemByPartnerID($user_id);
	return $package;
}
