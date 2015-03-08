<?php
$title = isset($title) ? $title : '';
$zip_entries = array();

if (!empty($file) && file_exists($file)) {
    $zip = new ZipArchive;
    if ($zip->open($file)) {
        for($x=0; $x < $zip->numFiles; $x++) {
            $zip_dir = &$zip_entries;
            $entry_name = $zip->getNameIndex($x);
            $path_nodes = explode('/', $entry_name);
            foreach ($path_nodes as $k=>$node) {
                if ($k < count($path_nodes)-1) {
                    if (!isset($zip_dir[$node])) {
                        $zip_dir[$node] = array();
                    }
                    $zip_dir = &$zip_dir[$node];
                } else {
                    $zip_dir[] = $node;
                }
            }
        }
        $zip->close();
    }
}

if (!function_exists('zipview_list_recursive')) {
    function zipview_list_recursive($zip_entries) {
        $html = '<ul class="zipview-files-list">';
        foreach ($zip_entries as $k=>$entry) {
            $html .= '<li class="zipview-entry">';
            if (is_array($entry)) {
                $html .= '<i class="glyphicon glyphicon-folder-open"></i>' . $k . zipview_list_recursive($entry);
            } else {
                $html .= '<i class="glyphicon glyphicon-file"></i>' . $entry;
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
?>
<style>
.zipview-files-list {
    list-style-type: none;
}
.zipview-entry i {
    margin-right: 5px;
}
</style>
<div class="zipview-container">
<h3 class="zipview-filename"><?php echo $title; ?></h3>
    <?php echo zipview_list_recursive($zip_entries); ?>
</div>
