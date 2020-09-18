<?php

function pagination_page($page)
{
	return (ctype_digit($page) && $page) ? $page : 1;
}

function pagination_offset($page, $limit)
{
	return (pagination_page($page) -1) * $limit;
}

function pagination($p=1, $url, $num_page, $num_record, $click='href', $extra='')
{                            
	$str = '<div class="box-footer clearfix">';
	$str = '<ul class="pagination pagination-sm no-margin pull-right">';
	if($num_page > 1){
		$pnumber = '';
		if($p > 1){
			$previous = ($p -1);
			$str .= '<li><a '.$click.'="'.$url.$previous.$extra.'" title="Next">&laquo;</a></li>';
		}
		if($p > 3) $str .= '<li><a '.$click.'="'.$url.'1'.$extra.'">1</a></li>';
		for($i=($p -2); $i < $p; $i++){
		  if($i < 1) continue;
		  $pnumber .= '<li><a '.$click.'="'.$url.$i.$extra.'">'.$i.'</a></li> ';
		}
		$pnumber .= '<li class="active"><a >'.$p.'</a></li> ';
		for($i=($p +1); $i < ($p +3); $i++){
		  if($i > $num_page) break;
		  $pnumber .= '<li><a '.$click.'="'.$url.$i.$extra.'">'.$i.'</a></li>';
		}
		$pnumber .= (($p +2) < $num_page ? '<li><a '.$click.'="'.$url.$num_page.$extra.'">'.$num_page.'</a></li>' : " ");
		$str .= $pnumber;
		if($p < $num_page){
			$next = ($p +1);
			$str .= '<li><a '.$click.'="'.$url.$next.$extra.'" title="Previous">&raquo;</a></li>';
		}
	}
	$str .= '</ul>';
	$str .= '<span>Total: <b>'.$num_record.'</b> data</span>';
	$str .= '</div>';
	return $str ;
}