<?php
class Convert
{
	public static function currencyDb($coin) {
		$coin = str_replace('.', '', $coin);
		$coin = str_replace(',', '.', $coin);
		return $coin;
	}

	public static function currencyBr($coin) {
		return number_format($coin, 2, ',', '.');
	}
}
?>