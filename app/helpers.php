<?php
	function sortFunction( $a, $b ) {
		return strtotime($a) - strtotime($b);
	}
	function uploadImage($image, $path, $imageName ,$height , $width )
    {
        $image = Image::make($image->getRealPath());
        
        $path = public_path() .'/'. $path;
        
        File::exists($path) or mkdir($path, 0777, true);
        
        $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/'.$imageName);

        return $imageName;
    }
	function updateBase64($files,$ref_ob)
    {
		 $imgob = str_replace('data:image/png;base64,','',$files);
		 $name = $ref_ob->refe_file_name;
		 $newname = uniqid()."_".$ref_ob->id.'.png';
		 $upath = $ref_ob->refe_file_path;
		 
		 $path = public_path('storage') .'/'. $upath;
		 
		 $imgob = str_replace('data:image/png;base64,','',$files);
		\File::put($path.'/'. $newname, base64_decode($imgob));
		
		 if(\File::exists($path.'/'. $name)) {
			unlink($path.'/'. $name);
		 }
		 if(\File::exists($path.'/thumb/'. $name)) {
			unlink($path.'/thumb/'. $name);
		 }
		 
		 $ref_ob->refe_file_name =  $newname;
		 $ref_ob->save();
		 
		return 1;
	}
	function uploadBase64($files,$upath ,$refe_table_field_name ,$ref_field_id , $type ,$imgs_priority)
    {
		$path = public_path('storage') .'/'. $upath;	
		$name = uniqid()."_".$ref_field_id.'.png';
		
		$dir = public_path('storage') .'/uploads/users';	\File::exists($dir) or File::makeDirectory($dir);
		$dir = public_path('storage') .'/uploads/issues';	\File::exists($dir) or File::makeDirectory($dir);
		
		\File::exists($path) or File::makeDirectory($path);
        $imgob = str_replace('data:image/png;base64,','',$files);
		\File::put($path.'/'. $name, base64_decode($imgob));
		
		if(filesize($path.'/'. $name) > 0){
			$requestData = array();
			$requestData['refe_file_path'] = $upath;
			$requestData['refe_file_name'] = $name;
			$requestData['refe_file_real_name'] = $name;
			$requestData['priority'] =  0;
			$requestData['refe_field_id'] = $ref_field_id;
			$requestData['refe_table_field_name'] = $refe_table_field_name;
			$requestData['refe_type'] = $type;
			\App\Refefile::create($requestData);
        
		}else{
			if (\File::exists($path.'/'. $name)) {
				unlink($path.'/'. $name);
			}
		}
		return 1;
		
    }
	function uploadModalReferenceFile($files,$upath ,$refe_table_field_name ,$ref_field_id , $type ,$imgs_priority)
    {
		ini_set('memory_limit', '-1');
		$path = public_path('storage') .'/'. $upath;	
		$path_thumb = public_path('storage') .'/'. $upath.'/thumb';	
		$upload = 0;
		
        foreach ($files as $i => $file) {

			$timestamp = uniqid();
			$real_name = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$name = $timestamp."_".$ref_field_id.".".$extension;
			
			$imgexist = \App\Refefile::where("refe_file_real_name",$real_name)->where("refe_field_id",$ref_field_id)->first();
			$ct = \App\Refefile::where("refe_table_field_name",$refe_table_field_name)->where("refe_field_id",$ref_field_id)->count();
			$size = $file->getSize();
			$file->move($path,$name);
			
			$requestData = array();
			$requestData['refe_file_path'] = $upath;
			$requestData['refe_file_name'] = $name;
			$requestData['refe_file_real_name'] = $real_name;
			$requestData['priority'] = (isset($imgs_priority[$real_name]))? $imgs_priority[$real_name] : ($ct+1);
			$requestData['refe_field_id'] = $ref_field_id;
			$requestData['refe_table_field_name'] = $refe_table_field_name;
			$requestData['refe_type'] = $type;
			$requestData['thumb_try'] = 0;
			$requestData['has_thumb'] = 0;
			$requestData['file_size'] = $size;
			\App\Refefile::create($requestData);
			$upload++;
		}
        
     
        return $upload;
		
    }
	function generatThumb()
    {
		$res = [];
		
		$nothumb = \App\Refefile::where("has_thumb",0)->where("thumb_try",0)->latest()->limit(50)->get();
		
		foreach($nothumb as $nth){
			$nth->thumb_try = $nth->thumb_try + 1;
			$nth->save();
			
			$path = public_path('storage') .'/'. $nth->refe_file_path;
			$path_thumb = $path.'/thumb';
			
			$path_info = pathinfo($nth->refe_file_name);
			
			if($nth->refe_file_name && \File::exists($path."/".$nth->refe_file_name)){
				
				if(! \File::exists($path_thumb."/".$nth->refe_file_name)){
					\File::exists($path_thumb) or mkdir($path_thumb, 0777, true);
			
					if(in_array($path_info['extension'],['jpg','jpeg','png','PNG','JPEG','JPG'])){
					
						$img = Image::make($path."/".$nth->refe_file_name,array(

							'width' => 100,

							'height' => 100,

							'grayscale' => false

						));

						$img->save($path_thumb.'/'.$nth->refe_file_name);
						
						$res[$nth->id][]="Created";
						
						$nth->has_thumb = 1;
						$nth->save();
						
					}
					$res[$nth->id][]="No image";
					
				}else{
					$res[$nth->id][]="File already exist :".$path_thumb."/".$nth->refe_file_name;
				}
				
			}else{
				$res[$nth->id][]="No file at location";
			}
		}
		return $res;
	}
	
	function removeRefeImage($refe)
    {
		if($refe){
		
			$path = public_path('storage');
			
			if ($refe->refe_file_name && $refe->refe_file_name !="" && \File::exists($path."/".$refe->refe_file_path."/".$refe->refe_file_name)) {
				unlink($path."/".$refe->refe_file_path."/".$refe->refe_file_name);
			}
			if ($refe->refe_file_name && $refe->refe_file_name !="" && \File::exists($path."/".$refe->refe_file_path."/thumb/".$refe->refe_file_name)) {
				unlink($path."/".$refe->refe_file_path."/thumb/".$refe->refe_file_name);
			}
			$refe->delete();
		}
	}    
?>