<?php   

/* Copyright (c) 2018-2019, Fares Abdullah, all rights reserved. */

namespace App\Teamup;

use GuzzleHttp\Client as GuzzleClient;

class Colors {

	/**
	 * https://apidocs.teamup.com/?php#colors
	 */
	public static $colors = array(
		1 => "f2665b",
		2 => "cf2424",
		3 => "a01a1a",
		4 => "7e3838",
		5 => "ca7609",
		6 => "f16c20",
		7 => "f58a4b",
		8 => "d2b53b",
		9 => "d96fbf",
		10 => "b84e9d",
		11 => "9d3283",
		12 => "7a0f60",
		13 => "542382",
		14 => "7742a9",
		15 => "8763ca",
		16 => "b586e2",
		17 => "668CB3",
		18 => "4770d8",
		19 => "2951b9",
		20 => "133897",
		21 => "1a5173",
		22 => "1a699c",
		23 => "0080a6",
		24 => "4aaace",
		25 => "88b347",
		26 => "5a8121",
		27 => "2d850e",
		28 => "176413",
		29 => "0f4c30",
		30 => "386651",
		31 => "00855b",
		32 => "4fb5a1",
		33 => "553711",
		34 => "724f22",
		35 => "9c6013",
		36 => "f6c811",
		37 => "ce1212",
		38 => "b20d47",
		39 => "d8135a",
		40 => "e81f78",
		41 => "f5699a",
		42 => "5c1c1c",
		43 => "a55757",
		44 => "c37070",
		45 => "000000",
		46 => "383838",
		47 => "757575",
		48 => "a3a3a3",
	);

	public static function getColor($colorId) {
		return Colors::$colors[$colorId];
	}
}
