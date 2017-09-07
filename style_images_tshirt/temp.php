<?php
function implodeArrayKeys($array) {
        return implode(",",array_keys($array));
}

$file = fopen("stylename.csv","r");

while(! feof($file))
  {
    $arrCsv = fgetcsv($file);
	$fileName = $arrCsv[0];
	
	$imageArr = array();			
			$directory = str_replace('and','&',$fileName);
			$directory = str_replace('-',' ',$directory);
			$directory = ucwords($directory);
			if($directory == '3d')
				$directory = '3D';
			$directory = 'Third_party/'.$directory.'/'; 
			$images = glob($directory . "*.png");
			foreach($images as $image)
			{
			  $pathinfo = pathinfo($image);
				
			  $imageArr[] = $pathinfo["basename"];
			}

			 
		   $new_array_set_string = implode(',',$imageArr);
		   $file2 = fopen($fileName.".csv","w");   
		   fputcsv($file2,array($new_array_set_string));
		   fclose($file2);				
  }

fclose($file);
?>