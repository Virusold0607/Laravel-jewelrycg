<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request; 
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Models\ProductsCategorie;
use App\Models\ProductTag;
use App\Models\ProductsVariant;
use App\Models\Attribute;
use App\Models\Material;
use App\Models\ProductMaterial;
use App\Models\ProductsTaxOption;
use App\Models\Upload;
use App\Models\ProductTagsRelationship;
use App\Models\MaterialTypeDiamonds;
use App\Models\MaterialType;
use App\Models\MaterialTypeDiamondsColor;
use App\Models\MaterialTypeDiamondsClarity;
use App\Models\MaterialTypeDiamondsPrices;
use App\Models\Step;
use App\Models\StepGroup;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.products.list', [
            'products' => Product::with('product_category')->orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * 
     * Display pending products that its status is 1
     */
    public function active(){
        return view('backend.products.active', [
            'products' => Product::where('status', 1)->with('product_category')->orderBy('id', 'DESC')->get()
        ]);        
    }
    /**
     * 
     * Display pending products that its status is 2
     */
    public function pending(){
        return view('backend.products.pending', [
            'products' => Product::where('status', 2)->with('product_category')->orderBy('id', 'DESC')->get()
        ]);        
    }
    public function trash()
    {
        return view('backend.products.trash', [
            'products' => Product::onlyTrashed()->orderBy('id', 'DESC')->get()
        ]);
    }

    public function get()
    {
        return datatables()->of(Product::query())
        ->addIndexColumn()
        ->addColumn('action', function($row){

               $btn = '<a href="'.route('products.show', $row->id).'" target="_blank" class="edit btn btn-info btn-sm">View</a>';
               $btn = $btn.'<a href="'.route('backend.products.edit', $row->id).'" class="edit btn btn-primary btn-sm">Edit</a>';
               $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Delete</a>';

                return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrStepGroups = StepGroup::pluck('name', 'id')->toArray();
        $arrSteps = Step::pluck('name', 'id')->toArray();

        return view('backend.products.create', [
            'attributes' => Attribute::orderBy('id', 'DESC')->get(),
            'categories' => ProductsCategorie::all(),
            'tags' => ProductTag::all(),
            'taxes' => ProductsTaxOption::all(),
            'arrStepGroups' => $arrStepGroups,
            'arrSteps' => $arrSteps,
        ]);
    }

    private function generateSlug($string)
    {
        return str_replace(' ', '-', $string);
    }

    private function registerNewTag($tag)
    {
        $blogtag = ProductTag::create([
            'name' => $tag,
            'slug' => $this->generateSlug($tag),
        ]);
        return $blogtag->id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $req)
    {
        $tags = (array)$req->input('tags');
        $variants = (array)$req->input('variant');
        $attributes = implode(",",(array)$req->input('attributes'));
        $values = implode(",",(array)$req->input('values'));
        $data = $req->all();
        $data['vendor'] = auth()->id();
        $data['price'] = Product::stringPriceToCents($req->price);
        $data['is_digital'] = $req->is_digital ? 1 : 0;
        $data['is_virtual'] = $req->is_virtual ? 1 : 0;
        $data['is_backorder'] = $req->is_backorder ? 1 : 0;
        $data['is_madetoorder'] = $req->is_madetoorder ? 1 : 0;
        $data['is_trackingquantity'] = $req->is_trackingquantity ? 1 : 0;
        $data['product_attributes'] = $attributes;
        $data['product_attribute_values'] = $values;
        $data['slug'] = str_replace(" ","-", strtolower($req->name));
        $slug_count = Product::where('slug', $data['slug'])->count();
        if($slug_count){
            $data['slug'] = $data['slug'].'-'.($slug_count+1);
        }
        $product = Product::create($data);
        $id_product = $product->id;

        foreach($variants as $variant)
        {
            $variant_data = $variant;
            $variant_data['product_id'] = $id_product;
            $variant_data['variant_price'] = Product::stringPriceToCents($variant_data['variant_price']);
            
            ProductsVariant::create($variant_data);
        }
        
        foreach( $tags as $tag )
        {
            $id_tag = (!is_numeric($tag)) ? $this->registerNewTag($tag) : $tag;
            ProductTagsRelationship::create([
                'id_tag' => $id_tag,
                'id_product' => $id_product,
             ]);
        }

        return redirect()->route('backend.products.edit', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::whereId($id)->with(['tags', 'variants', 'variants.uploads'])->firstOrFail();
        $product->setPriceToFloat();

        $variants = ProductsVariant::where('product_id', $id)->get();

        $variants->each(function($product){
            $product->setPriceToFloat();
        });

        $selected_attributes = explode(',', $product->product_attributes);
        $prepare_values  = Attribute::whereIn('id', $selected_attributes)->with(['values'])->get();
        $seller = User::query()->find($product->vendor);

        $arrMaterials = Material::with('types')->get();
        $arrProductMaterials = ProductMaterial::getMaterialsByProduct($product->id);
        $arrDiamondTypes = MaterialTypeDiamonds::where('material_id','=', '1')->get();
        $arrDiamondTypeColors = MaterialTypeDiamondsColor::all();
        $arrDiamondTypeClarity = MaterialTypeDiamondsClarity::all();
        $user_id = Auth::id();
        $arrDiamondTypePrices = MaterialTypeDiamondsPrices::where('user_id',$user_id)->get()->toArray();
        $diamondPrices = [];
        foreach ($arrDiamondTypePrices as $key => $value) {
            $diamondPrices[$value['diamond_id']] = $value;
        }

        $arrStepGroups = StepGroup::pluck('name', 'id')->toArray();
        $arrSteps = Step::pluck('name', 'id')->toArray();

        return view('backend.products.edit', [
            'product' => $product,
            'variants' => $variants,
            'categories' => ProductsCategorie::all(),
            'attributes' => Attribute::orderBy('id', 'DESC')->get(),
            'tags' => ProductTag::all(),
            'uploads' => Upload::whereIn('id', explode(',',$product->product_images))->get(),
            'selected_values' => $prepare_values,
            'seller' => $seller,
            'taxes' => ProductsTaxOption::all(),
            'arrProductMaterials' => $arrProductMaterials,
            'arrDiamondTypes' => $arrDiamondTypes,
            'arrMaterials' => $arrMaterials,
            'arrSteps' => $arrSteps,
            'arrStepGroups' => $arrStepGroups,
            'arrDiamondTypeColors' => $arrDiamondTypeColors,
            'arrDiamondTypeClarity' => $arrDiamondTypeClarity,
            'diamondPrices' => $diamondPrices,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStoreRequest $req, $product)
    {
        $counter = Product::where('slug', $req->slug)->count();
        $sep = ($counter==0) ? '' : '-'.$counter+1;
        $tags = (array)$req->input('tags');
        $variants = (array)$req->input('variant');
        $attributes = implode(",",(array)$req->input('attributes'));
        $values = implode(",",(array)$req->input('values'));
        $data = $req->all();
        $data['price'] = Product::stringPriceToCents($req->price);
        $data['is_digital'] = ($req->is_digital)? 1 : 0;
        $data['is_virtual'] = ($req->is_virtual) ? 1 : 0;
        $data['is_backorder'] = ($req->is_backorder & $req->is_backorder == 1) ? 1 : 0;
        $data['is_madetoorder'] = ($req->is_madetoorder & $req->is_madetoorder == 1) ? 1 : 0;
        $data['is_trackingquantity'] = $req->is_trackingquantity ? 1 : 0;
        $data['product_attributes'] = $attributes;
        $data['product_attribute_values'] = $values;
        $data['category'] = $req->get('category');
        if($req->slug == "")
        {
            $data['slug'] = str_replace(" ","-", strtolower($req->name)).$sep;
        }
        $user_id = Auth::id();
        $product = Product::findOrFail($product);
        $product->update($data);
        ProductTagsRelationship::where('id_product', $product->id)->delete();

        // product material 
        if (isset($data['product_material_id'])) {
            $product_material_id = $data['product_material_id'];
        }
        if(isset($data['deleted_material_ids'])) {
            foreach($data['deleted_material_ids'] as $deleted_item){
                $material = ProductMaterial::findOrFail($deleted_item);
                $material->delete();
            }
        }
        if(isset($data['diamond_id'])) {
            $i = 0;
            foreach ($data['product_material_id'] as $item) {
                if($data['material_id'][$i] == 1) {
                    if(!$product_material_id[$i]) {
                        $temp['product_id'] = $product->id;
                        $temp['material_id'] = $data['material_id'][$i];
                        $temp['material_type_id'] = $data['material_type_id'][$i];
                        $temp['diamond_id'] = $data['diamond_id'][$i];
                        $temp['is_diamond'] = 1;
                        $temp['diamond_amount'] = $data['diamond_amount'][$i];            
                        $temp['material_weight'] = '';
                        ProductMaterial::create($temp);
                    } else {
                        $product_material = ProductMaterial::find($product_material_id[$i]);
                        $temp['product_id'] = $product->id;
                        $temp['material_id'] = $data['material_id'][$i];
                        $temp['material_type_id'] = $data['material_type_id'][$i];
                        $temp['diamond_id'] = $data['diamond_id'][$i];
                        $temp['is_diamond'] = 1;
                        $temp['diamond_amount'] = $data['diamond_amount'][$i];            
                        $temp['material_weight'] = '';
                        $product_material->update($temp);
                    }
                    $diamond_prices = MaterialTypeDiamondsPrices::where('user_id',$user_id)->where('diamond_id',$data['diamond_id'][$i])->first();
                    if(isset($diamond_prices)){
                        $price['diamond_id'] = $data['diamond_id'][$i];
                        $price['color'] = $data['diamond_color'][$i];
                        $price['clarity'] = $data['diamond_clarity'][$i];
                        $price['lab_price'] = $data['lab_price'][$i];
                        $price['natural_price'] = $data['natural_price'][$i];
                        $diamond_prices->update($price);
                    } else {
                        $price['user_id'] = $user_id;
                        $price['diamond_id'] = $data['diamond_id'][$i];
                        $price['color'] = $data['diamond_color'][$i];
                        $price['clarity'] = $data['diamond_clarity'][$i];
                        $price['lab_price'] = $data['lab_price'][$i];
                        $price['natural_price'] = $data['natural_price'][$i];
                        MaterialTypeDiamondsPrices::create($price);
                    }
                } else {    
                    if(!$product_material_id[$i]) {            
                        $temp['product_id'] = $product->id;
                        $temp['material_id'] = $data['material_id'][$i];
                        $temp['material_type_id'] = $data['material_type_id'][$i];
                        $temp['diamond_id'] = 0;
                        $temp['is_diamond'] = 0;
                        $temp['material_weight'] = $data['material_weight'][$i];
                        $temp['diamond_amount'] = '';
                        ProductMaterial::create($temp);
                    } else {
                        $product_material = ProductMaterial::find($product_material_id[$i]);            
                        $temp['product_id'] = $product->id;
                        $temp['material_id'] = $data['material_id'][$i];
                        $temp['material_type_id'] = $data['material_type_id'][$i];
                        $temp['diamond_id'] = 0;
                        $temp['is_diamond'] = 0;
                        $temp['material_weight'] = $data['material_weight'][$i];
                        $temp['diamond_amount'] = '';
                        $product_material->update($temp);
                    }
                }
                $i++;
            }
        }


        // product variant

        $variantIds = [];
        foreach($variants as $variant)
        {
            $variantIds[] = $variant['id'];
        }
        ProductsVariant::where('product_id', $product->id)->whereNotIn('id', $variantIds)->delete();

        foreach($variants as $variant)
        {
            $variant_data = $variant;
            $variant_data['product_id'] = $product->id;
            $variant_data['variant_price'] = Product::stringPriceToCents($variant_data['variant_price']);

            ProductsVariant::updateOrCreate(['product_id' => $product->id, 'variant_attribute_value' => $variant['variant_attribute_value']], $variant_data);
        }
        
        foreach( $tags as $tag )
        {
            $id_tag = (!is_numeric($tag)) ? $this->registerNewTag($tag) : $tag;
            ProductTagsRelationship::create([
                'id_tag' => $id_tag,
                'id_product' => $product->id
             ]);

        }
        cache()->forget('todays-deals');

        return redirect()->route('backend.products.edit', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('backend.products.list');
    }

    public function recover($id)
    {
        Product::withTrashed()->find($id)->restore();
        return redirect()->route('backend.products.trash');
    }

    public function update_digital_assets(Request $request, $id) {
        return Product::where('id', $id)->update(['digital_download_assets' => $request->value]);
    }

    public function update_variant_assets(Request $request, $id) {
        return ProductsVariant::where('id', $id)->update(['digital_download_assets' => $request->value]);
    }
}
