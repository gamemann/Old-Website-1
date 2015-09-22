<?php
	class convars {
		protected $cvars;
		function getdata($cvar) {
			for ($i=0; $i < count($this->cvars); $i++) {
				if (isset($this->cvars[trim($cvar, ' ')])) {
					return $this->cvars[trim($cvar, ' ')];
				} else {
					echo '[ERROR]Convar not found!';
				}
			}
		}
		
		function init() {
			$this->cvars = array(
				'cvar_debug' => '0',
				'cvar_announce' => '1'
			);
		}
	}
	$cvars = new convars;
	$cvars->init();
	
	class friendlist {
		protected $flags;
		protected $accountid;
		protected $pflags;
		protected $cvars;
		
		function AddFriend($id, $flags) {
			/*
				Flags:
					NO_NOTIFICATION
					REMOVABLE
					AUTO_ACCEPT
			*/
			$flags = preg_split('/[|]/', $flags);
			for ($i=0; $i < count($flags); $i++) {
				for ($t=0; $t < count($this->flags); $t++) {
					if ($this->flags[$t] == trim($flags[$i], ' ')) {
						if ($this->cvars->getdata('cvar_debug')) {
							echo 'Flag Complete: ' . trim($flags[$i], ' ') . '<br />';
						}
					}
				}
			}
			if ($this->cvars->getdata('cvar_announce')) {
				echo 'You\'ve added ' . $this->getname($id) . ' to your friend\'s list!';
			}
		}
		
		function RemoveFriend($id, $flags) {
			/*
				Flags:
					NO_NOTIFICATION
					BLOCK
			*/
		}
		
		function getname($id) {
			return $id;
		}
		
		function Init() {
			$this->accountid = 1;
			$this->pflags = '';
			$this->flags = array(
				'0' => 'NO_NOTIFICATION',
				'1' => 'REMOVABLE',
				'2' => 'AUTO_ACCEPT',
				'3' => 'BLOCK'
			);
			
			$this->cvars = new convars;
			$this->cvars->init();
		}
	}
	
	$fl = new friendlist;
	
	$fl->init();
	$fl->AddFriend(1, 'NO_NOTIFICATION | REMOVABLE');
?>