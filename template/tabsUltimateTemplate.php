<?php 


//$tabs_ultimate_test = tabs_ultimate_get_option('test_text');
?>


<div id="tabs">
	<ul>
<?php
	
	$tu_tab_count = 0;
	foreach ( (array) $tabGroup as $key => $entry ) {
		$tu_tab_count += 1;
	    $title = $desc = '';
	
	    if ( isset( $entry['title'] ) )
	        $title = esc_html( $entry['title'] );
		echo '<li><a href="#fragment-'.$tu_tab_count.'">'.$title.'</a></li>';
	}
?>
	</ul>
<?php
	
	$tu_tab_count = 0;
	foreach ( (array) $tabGroup as $key => $entry ) {
		$tu_tab_count += 1;
	    $title = $desc = '';
	
	    if ( isset( $entry['description'] ) )
	        $desc = wpautop( $entry['description'] );
		echo '<div id="fragment-'.$tu_tab_count.'">'.$desc.'</div>';
	}
?>
</div>
