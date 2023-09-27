<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 07/09/19
 * Time: 1:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProjectDetails extends Model
{

    protected $fillable = ['id','project_id','sub_projects','payment_date','payment_amount','payment_method',
        'cheque_number','payment_collected_by','payment_notes','expense_type','pay_to','description',
        'expense_notes','expense_date','paid_date','ordered_by', 'status','estimate_date','estimate_time',
        'referred_by','estimate_description','estimate_description_amount','estimate_subtotal','estimate_discount_value','estimate_net_amount',
        'is_paint_material_included','deposit_amount','interior_deposit_amount','exterior_deposit_amount','discount_amount','discount_type','discount_payment_method','special_notes','final_price',
        'paint_area','coat_1','coat1_gallons','coat_2','coat2_gallons','trim','trim_coats','trim_gallons','ceiling','ceiling_coats','ceiling_gallons',
        'closet','price','is_fix_sheetrock','is_skim_sheetrock','is_strip_wallpaper','carpentery','is_porter','is_duron','is_glidden','is_benjamin_moore',
        'is_sherwin_williams','is_other_paint','ceilings','walls','trims','others','is_interior_paint_material_included','interior_discount_amount','interior_discount_value','interior_subtotal','interior_net_amount','carpentery_amount',
        'interior_discount_type','interior_payment_method','interior_special_notes','interior_final_price','is_house','is_gutters',
        'is_decks','is_driveway','is_patio','is_fence','pressure_wash_notes','pressure_wash_price','is_scrape_prime','is_scrape_price','is_prime_window',
        'is_putty','is_windows','is_sliding','is_ground_doors','is_cover_plant', 'is_doors','is_cornice','is_brick','is_metal','is_reglaze_windows',
        'is_silicone_caulk_notes','recaulk', 'is_windows_price','is_stucco','is_stucco_brick','is_stucco_metal','is_concrete','is_bay_tops_notes',
        'caulk','stucco_price','prime_assignment','is_prime_siding','is_prime_trim','is_prime_windows','is_prime_new_wood','is_prime_brick','is_prime_metal',
        'prime_coats','prime_gallons','prime_notes','paint','prime_price','siding_type','siding_assigned_to','siding_color','siding_paint',
        'siding_coats','siding_gallons','siding_finish','siding_price','trim_type','trim_assigned_to','trim_color','trim_paint','trim_coat','trim_gallon',
        'trim_finish','trim_price','shutter_type','shutter_assigned_to','shutter_color','shutter_paint','shutter_coats','shutter_gallons','shutter_finish','shutter_price','is_front_door_prime',
        'is_front_door_paint','front_door_coats','front_door_gallons','front_door_notes','front_door_price','is_bay_tops_paint','bay_tops_price', 'is_bay_tops_copper','iron_railing_strip','iron_railing_prime','iron_railing_paint','iron_railing_notes','bay_tops_price',
        'is_porch_outside','is_porch_inside','is_porch_ceiling','is_porch_cover','is_porch_floor','is_porch_seal','is_porch_stain','is_porch_paint','porch_price','is_decks_clean','is_decks_seal',
        'is_decks_prime','is_decks_paint','is_decks_stain','decks_color','decks_coats','decks_assigned_to','decks_paint','decks_finish','decks_gallons','decks_price','is_seal_cracks',
        'is_seal_around_trim','is_seal_dow_corning','seal_color','seal_coats','seal_price','is_elastromeric_paint','is_spray_black_roll','is_paint_stucco_trim','paint_coats','paint_gallons','paint_assigned_to',
        'paint_color','paint_price','paint_finish','paint_notes','carpentry','carpentry_price','others_notes','other_price','is_price_include_paint_material','price_subtotal','exterior_discount_type','exterior_discount_amount',
        'exterior_payment_method','exterior_payment_amount','exterior_special_notes','exterior_price','exterior_discount_value','exterior_net_amount','interior_description_area'
    ];

    public function interiorpaints()
    {
        return $this->hasMany('App\Models\InteriorPaints');
    }

    public function interiordescription()
    {
        return $this->hasMany('App\Models\InteriorDescription');
    }

    public function otherdescription()
    {
        return $this->hasMany('App\Models\OtherDescription');
    }

}
