<?php

class UserBuy
{
	public $CountProduct = 0;
	public $CountMoney = 0;
	
	public function __construct()
	{
		if(isset($_POST['SIMPLE_BUY_ID']))
		{
			$IdProduct = $_POST['SIMPLE_BUY_ID'];
			$CountProduct = $_POST['SIMPLE_COUNT_PRODUCT'];
			$money = $_POST['SIMPLE_MONEY'];
			if(is_numeric($CountProduct) && is_numeric($CountProduct) && is_numeric($money))
			{
				$_SESSION['SIMPLE_COUNT_MONEY'] = ($money*$CountProduct)+$_SESSION['SIMPLE_COUNT_MONEY'];
				$this->FindID();
				$_SESSION['COUNT_PRODUCT'] = $CountProduct+$_SESSION['COUNT_PRODUCT'];

				$this->CountMoney = $_SESSION['SIMPLE_COUNT_MONEY'];
				$this->CountProduct = $_SESSION['COUNT_PRODUCT'];
			}
		}
		else
		{
			if(empty($_SESSION['SIMPLE_COUNT_MONEY'])) $_SESSION['SIMPLE_COUNT_MONEY'] = 0;
			if(empty($_SESSION['SIMPLE_LIST_BUY']))$_SESSION['SIMPLE_LIST_BUY'] = '';
			if(empty($_SESSION['COUNT_PRODUCT'])) $_SESSION['COUNT_PRODUCT'] = 0;

			$this->CountMoney = $_SESSION['SIMPLE_COUNT_MONEY'];
			$this->CountProduct = $_SESSION['COUNT_PRODUCT'];

		}
	}

	private function FindID()
	{
		$status = false;
		if($_SESSION['SIMPLE_LIST_BUY'] != '')
		{
			foreach ($_SESSION['SIMPLE_LIST_BUY'] as $id=>$count)
			{
				if($id == $_POST['SIMPLE_BUY_ID'])
				{
					$_SESSION['SIMPLE_LIST_BUY'][$id] = $count+$_POST['SIMPLE_COUNT_PRODUCT'];
					$status = true;
				}
			}
		}
		if($status == false) $_SESSION['SIMPLE_LIST_BUY'][$_POST['SIMPLE_BUY_ID']] = $_POST['SIMPLE_COUNT_PRODUCT'];
	}

	public function clear()
	{
		$_SESSION['SIMPLE_COUNT_MONEY'] = 0;
		//$_SESSION['SIMPLE_LIST_BUY'] = '';
		$_SESSION['COUNT_PRODUCT'] = 0;
	}

}

?>