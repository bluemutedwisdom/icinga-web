<?php

class AppKit_PrincipalAdminModel extends ICINGAAppKitBaseModel
{
	
	public function __construct() {
		
	}
	
	public function getTargetArray() {
		
		$out = array ();
		
		foreach (Doctrine::getTable('NsmTarget')->findAll() as $r) {
			
			$out[$r->target_name] = array (
				'id'			=> $r->target_id,
				'name'			=> $r->target_name,
				'description'	=> $r->target_description,
				'type'			=> $r->target_type,
				'fields'		=> array ()
			);
			
			foreach ($r->getTargetObject()->getFields() as $fname=>$fdesc) {
				$out[$r->target_name]['fields'][$fname] = $fdesc;
			}
			
		}
		
		return $out;
	}
	
	public function getSelectedValues($principal_id) {
		$r = Doctrine_Query::create()
		->select('pt.pt_principal_id, tv.*, t.*')
		->from('NsmPrincipalTarget pt')
		->leftJoin('pt.NsmTargetValue tv')
		->innerJoin('pt.NsmTarget t')
		->andWhere('pt.pt_principal_id=?', array($principal_id))
		->execute();
		
		$out = array ();
		
		foreach ($r as $pt) {
			$out[$pt->NsmTarget->target_name][$pt->pt_id] = array ();
			
			$v = array ();
			foreach ($pt->NsmTargetValue as $tv) {
				$v[$tv->tv_key] = $tv->tv_val;
			}
			$out[$pt->NsmTarget->target_name][$pt->pt_id] = $v;
		}
		
		return $out;
	}
	
	public function updatePrincipalValueData(NsmPrincipal &$p, array $pt, array $pv) {
		
		// First delete all entries we create new ones
		$this->deleteAllPrincipalTargetEntries($p);
		
		// var_dump(array($pt, $pv));
		
		foreach ($pt as $target_id => $pt_garbage) {
			if (isset($pt_garbage['set'])) {
				foreach ($pt_garbage['set'] as $aid=>$pt_set) {
					if ($pt_set == 1) {
						
						$target = Doctrine::getTable('NsmTarget')->find($target_id);
						
						$principal_target = new NsmPrincipalTarget();
						$principal_target->NsmPrincipal = $p;
						$principal_target->NsmTarget = $target;
						
						if (isset($pv[$target_id])) {
							foreach ($pv[$target_id] as $pv_key => $pv_data) {
								
								$pv_val = null;
								if (isset($pv_data[$aid])) {
									$pv_val = $pv_data[$aid];
								}
								
								
								
								$target_value = new NsmTargetValue();
								$target_value->tv_key = $pv_key;
								$target_value->tv_val = $pv_val;
								
								$principal_target->NsmTargetValue[] = $target_value;
								
								
							}
						}
						
						$principal_target->save();
					}
				}
			}
		} 
	}
	
	private function deleteAllPrincipalTargetEntries(NsmPrincipal &$p) {
		Doctrine_Manager::connection()->beginTransaction();
		
		foreach ($p->NsmPrincipalTarget as $pt) {
			$pt->NsmTargetValue->delete();
			$pt->delete();
		}
				
		Doctrine_Manager::connection()->commit();
		
	}
	
}

?>