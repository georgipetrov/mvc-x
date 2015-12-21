<?php
class MasterxController extends Controller {
	public function index() {

	}
	
	public function mvc_generator() {
		$this->pageTitle 		= 'MVC Generator';
		$this->load->model('masterx');
		$dbtables = $this->masterx->query('show tables;');
		$this->set('dbtables',$dbtables);
	}
	
	public function mvc_generator_generate() {
		$this->autoRender = false;
		$app_path = SITE_PATH.DS.'app'.DS.$this->app->dir;
		$x_folderpath = SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X;
		$samplefiles_path = SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X.DS.'masterx'.DS.'view'.DS.'masterx'.DS.'template'.DS.'default'.DS.'view';

		if (isset($this->request->post)) {
			$post = $this->request->post;
			$error = '';
			//Create folder
			$target_dirs['base'] = $x_folderpath.DS.$post['table'];
			$target_dirs['model'] = $x_folderpath.DS.$post['table'].DS.'model';
			$target_dirs['view'] = $x_folderpath.DS.$post['table'].DS.'view';
			$target_dirs['view_inner'] = $x_folderpath.DS.$post['table'].DS.'view'.DS.$post['table'];
			$target_dirs['controller'] = $x_folderpath.DS.$post['table'].DS.'controller';
			
			foreach ($target_dirs as $dir) {
				if (!file_exists($dir)) {
					mkdir($dir);
				} else {
					$error = 'Directory '.$dir. ' already exists. Please choose another table. No MVC was created.';
				}	
			}

			//View : Index
			if (isset($post['field']['index']['_status_']) && $post['field']['index']['_status_'] == 1) {
				$view_index = file_get_contents($samplefiles_path.DS.'sample'.DS.'index.tpl');
				$table_heading = '';
				$table_rows = '';
				foreach ($post['field']['index'] as $field => $properties) {
					if ($field == '_status_' || $field == '_title_' || $properties['name'] == "") {
						continue;
					}
					// Table heading
					$table_heading .= str_replace('{Title}',$properties['name'],file_get_contents($samplefiles_path.DS.'element'.DS.'table-heading.tpl'));					
					$table_rows .= '<td><?php echo $p["'.$field.'"]; ?></td>'.PHP_EOL;					
				}
				$view_index = str_replace('{table-heading.tpl}',$table_heading, $view_index);
				$view_index = str_replace('{rows}',$table_rows, $view_index);
				$view_index = str_replace('{ViewTitle}',$post['field']['index']['_title_'], $view_index);
				$view_index = str_replace('{base}',$post['table'], $view_index);
				file_put_contents($target_dirs['view_inner'].DS.'index.tpl',$view_index);
			}

			//View : View
			if (isset($post['field']['view']['_status_']) && $post['field']['view']['_status_'] == 1) {
				$view_view = file_get_contents($samplefiles_path.DS.'sample'.DS.'view.tpl');
				$view_boxes = '';
				foreach ($post['field']['view'] as $field => $properties) {
					if ($field == '_status_' || $field == '_title_' || $properties['name'] == "" || $properties['field'] == "") {
						continue;
					}
					// Table heading
					$box_type = $properties['inputtype'];
					$box_field = $properties['field'];
					$box_name = $properties['name'];
					$view_boxes .= PHP_EOL."<div class='view-row'>\n<h3>$box_name</h3>\n<div>".'<?php echoine($persistence[\''.$box_field.'\']); ?>'."</div>\n</div>";					
				}
				$view_view = str_replace('{ViewBoxes}',$view_boxes, $view_view);
				$view_view = str_replace('{ViewTitle}',$post['field']['view']['_title_'], $view_view);
				file_put_contents($target_dirs['view_inner'].DS.'view.tpl',$view_view);
			}

			//View : Edit
			if (isset($post['field']['edit']['_status_']) && $post['field']['edit']['_status_'] == 1) {
				$view_edit = file_get_contents($samplefiles_path.DS.'sample'.DS.'edit.tpl');
				$edit_boxes = '';
				foreach ($post['field']['edit'] as $field => $properties) {
					if ($field == '_status_' || $field == '_title_' || $properties['name'] == "" || $properties['field'] == "") {
						continue;
					}
					// Table heading
					$box_type = $properties['inputtype'];
					$box_field = $properties['field'];
					$box_name = $properties['name'];
					$edit_boxes .= PHP_EOL."[bs:input type=$box_type title=$box_name name=$box_field value=".'<?php echoine($persistence[\''.$box_field.'\']); ?>'."]";					
				}
				$view_edit = str_replace('{EditBoxes}',$edit_boxes, $view_edit);
				$view_edit = str_replace('{EditTitle}',$post['field']['edit']['_title_'], $view_edit);
				file_put_contents($target_dirs['view_inner'].DS.'edit.tpl',$view_edit);
			}
			
			//View : Add
			if (isset($post['field']['add']['_status_']) && $post['field']['add']['_status_'] == 1) {
				$view_add = file_get_contents($samplefiles_path.DS.'sample'.DS.'add.tpl');
				$add_boxes = '';
				foreach ($post['field']['add'] as $field => $properties) {
					if ($field == '_status_' || $field == '_title_' || $properties['name'] == "" || $properties['field'] == "") {
						continue;
					}
					// Table heading
					$box_type = $properties['inputtype'];
					$box_field = $properties['field'];
					$box_name = $properties['name'];
					$add_boxes .= PHP_EOL."[bs:input type=$box_type title=$box_name name=$box_field value=".'<?php echoine($persistence[\''.$box_field.'\']); ?>'."]";					
				}
				$view_add = str_replace('{AddBoxes}',$add_boxes, $view_add);
				$view_add = str_replace('{AddTitle}',$post['field']['add']['_title_'], $view_add);
				file_put_contents($target_dirs['view_inner'].DS.'add.tpl',$view_add);
			}
			
			//Config
			$config_file = file_get_contents($samplefiles_path.DS.'..'.DS.'xconfig.php');
			file_put_contents($target_dirs['base'].DS.'xconfig.php',$config_file);


			//Controller
			$controller_file = file_get_contents($samplefiles_path.DS.'..'.DS.'controller'.DS.'sample.php');
			$controller_file = str_replace('Sample',ucfirst($post['table']),$controller_file);
			file_put_contents($target_dirs['base'].DS.'controller'.DS.$post['table'].'.php',$controller_file);
			//Model
			$model_file = file_get_contents($samplefiles_path.DS.'..'.DS.'model'.DS.'sample.php');
			$model_file = str_replace('Sample',ucfirst($post['table']),$model_file);
			file_put_contents($target_dirs['base'].DS.'model'.DS.$post['table'].'.php',$model_file);
		
		}
	}
	
	public function mvc_generator_tablefields($table = '') {
		$this->load->model('masterx');
		if (!empty($table)) {
			$tablefields = $this->masterx->query('SHOW COLUMNS FROM '.$table.';');
		} else {
			$tablefields = array();
		}
		
		$this->set('views',array('index','add','view','edit'));
		$this->set('tablefields',$tablefields);

	}
	
	public function mvc_generator_success($table = '') {
		$this->set('table',$table);

	}
	
}