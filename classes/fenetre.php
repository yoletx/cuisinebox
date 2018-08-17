<?php
	class Fenetre {
		protected $header;
		protected $content;

		public function afficher_header(){
			echo $this->header;
		}
		public function afficher(){
			echo $this->content;
		}


	}
?>