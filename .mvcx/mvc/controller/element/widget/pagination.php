<?php
class WidgetPaginationController extends Controller {
    public function beforeRender() {
       
	   $pages= array();
	   $uri = returnine($_SERVER['REQUEST_URI']);
	   $first_page = 1;
	   $current_page = returnine($this->request->data['_paginate_current_page_number_'],1);
	   $last_page = returnine($this->request->data['_paginate_total_page_number_'],1);
	   $total_results = returnine($this->request->data['_paginate_all_results_count_'],1);
	   $results_per_page = returnine($this->request->data['_paginate_results_per_page_'],1);
       $uri = str_replace(array('?pagenumber='.$current_page,'&pagenumber='.$current_page),'',$uri);
	   $glue = '?';
	   if (strpos($uri,'?') !== false) {
			$glue = '&';   
	   }
	   for ($i = $first_page; $i <=$last_page; $i++) {
			$pages[] = array('link'=>$uri.$glue.'pagenumber='.$i,'number'=>$i);   
	   }
	   
	   $filteredpages = $pages;
	   if (count($pages) > 6) {
		   $start_page = $current_page - 3;
		   $end_page = $current_page + 3;
		   if ($start_page < 1) { 
		   		$start_page = 1;
		   		$end_page = 7;
		   }
		   if ($end_page > count($pages)) { 
		   		$end_page = count($pages);
		    	$start_page = count($pages) - 6;
			}
		   
		   foreach ($filteredpages as $k => $page) {
				if ($k+1 >= $start_page && $k+1 <= $end_page) {
					
				} else {
					unset($filteredpages[$k]);	
				}
		   }
	   }
	   
		$this->set('pages', $pages);
		$this->set('filteredpages', $filteredpages);
		$this->set('first_page', $first_page);
		$this->set('current_page', $current_page);
		$this->set('last_page', $last_page);
		$this->set('total_results', $total_results);
		$this->set('results_per_page', $results_per_page);
    }
}
