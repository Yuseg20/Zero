<?php
	class IndexController extends Controller{

		//主页
		public function index(){
			$this -> set('online', '666');

			$this -> display('main.html');
		}
	}
?>