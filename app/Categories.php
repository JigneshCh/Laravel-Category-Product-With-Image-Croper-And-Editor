<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
	use SoftDeletes;
	protected $table = 'categories';

    
    protected $primaryKey = 'id';
	
    protected $fillable = ['name','slug','icon','parent_id','display_order','created_by','updated_by','quote_desc'];
	
	protected $appends = ['order_label'];
	
	public function getOrderLabelAttribute()
    {
		if($this->parent){
			return $this->parent->display_order.".".$this->display_order;
		}else{
			return $this->display_order;
		}
	}
	
	public function next()
    {
		if($this->parent){
			$cat = Categories::where("display_order",">",$this->display_order)->where("parent_id",$this->parent_id)->orderby("display_order","asc")->first();
			if($cat){
				return $cat;
			}
		}
		
		return null;
	}
	public function previous()
    {
		if($this->parent){
			$cat = Categories::where("display_order","<",$this->display_order)->where("parent_id",$this->parent_id)->orderby("display_order","DESC")->first();
			if($cat){
				return $cat;
			}
		}
		
		return null;
	}

    public function child()
    {
        return $this->hasMany('App\Categories', 'parent_id', 'id')->select(['id','name','slug','parent_id','display_order','quote_desc','is_hidden'])->with('refefile')->orderby('display_order','asc');
    }
	public function fullchild()
    {
        return $this->hasMany('App\Categories', 'parent_id', 'id')->with('refefile')->orderby('display_order','asc');
    }
	public function parent()
    {
        return $this->belongsTo('App\Categories', 'parent_id', 'id');
    }
	 public function scopeParentonly($query)
    {
        return $query->where('parent_id', 0);
    }
	public function scopeChildonly($query)
    {
        return $query->where('parent_id','>',0);
    }
	public function refefile()
    {
        return $this->hasMany('App\Refefile', 'refe_field_id', 'id')->where('refe_table_field_name', 'cat_id');
    }
	public function issue()
    {
        return $this->hasMany('App\Issue', 'child_category_id', 'id');
    }
	
}
