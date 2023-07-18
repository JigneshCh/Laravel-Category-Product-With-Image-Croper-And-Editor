<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
	use SoftDeletes;
	protected $table = 'issue';

    
    protected $primaryKey = 'id';
	
    protected $guarded = [];
	
	protected $appends = ['created_tz','created_a'];

	public function getCreatedTzAttribute()
    {
        if($this->created_at != ""){
            return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
        }
		return $this->created_at;
    }
	public function getCreatedAAttribute()
    {
		if($this->created_at != ""){
           return \Carbon\Carbon::parse($this->created_at)->format(\config('settings.date_format_on_app'));
        }
		return $this->created_at;
    }
    public function category()
    {
        return $this->belongsTo('App\Categories', 'category_id', 'id');
    }
	
	
	public function subcategory()
    {
        return $this->belongsTo('App\Categories', 'child_category_id', 'id');
    }
	public function surveycategory()
    {
        return $this->belongsTo('App\Surveycategories', 'child_unique_id', 'unique_id');
    }
	
	public function refefile()
    {
        return $this->hasMany('App\Refefile', 'refe_field_id', 'id')->where('refe_table_field_name', 'issue_id')->orderBy('priority','asc')->orderBy('id','asc');
    }
	
	public function allRefeFile()
    {
        return $this->hasMany('App\Refefile', 'refe_field_id', 'id')->whereIn('refe_table_field_name',['issue_id_cost','issue_cat_image','issue_id'] );
    }
	
	public function getsubcategory()
    {
        if($this->subcategory){
			return $this->belongsTo('App\Categories', 'child_category_id', 'id');
		}else{
			return $this->belongsTo('App\Surveycategories', 'child_unique_id', 'unique_id');
		}
    }
	public function next(){
        return Issue::where('id', '>', $this->id)->where('survey_id', $this->survey_id)->orderBy('id','asc')->first();
	}
    public function previous(){
        return Issue::where('id', '<', $this->id)->where('survey_id', $this->survey_id)->orderBy('id','desc')->first();
	}
	public function hintImages(){
		$img_hint = [];
        if($this->img_hint && $this->img_hint != ""){
			$img_hint = json_decode($this->img_hint,true);
		}
		foreach($this->refeCatfile as $rf){
			$img_hint[] = ["img_id"=>$rf->id,"hint"=>""];
		}
		if(count($img_hint)){
			usort($img_hint, function($a, $b) {
				return $a['img_id'] <=> $b['img_id'];
			});
		}
		return $img_hint;
	}
}















