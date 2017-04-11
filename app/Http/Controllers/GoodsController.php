<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use App\Http\Filters\GoodsListFilter;
use App\Http\Requests\AddGoodsRequest;
use App\Models\Goods;
use Illuminate\Http\Request;

class GoodsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function goodsList(Request $request, GoodsListFilter $filter)
    {

        $filters                = $request->all();
        $goodsCollectionBuilder = Goods::query();
        $goodsCollectionBuilder = $filter->filter($goodsCollectionBuilder, $filters);
        $goodsCollection        = $goodsCollectionBuilder->paginate(10, [ '*' ],
            'messagePage')->setPageName('messagePage');

        return view('goods.list', compact('goodsCollection', 'filters'));
    }

    public function goodsDelete($goodsId, Request $request)
    {
        if ($goods = Goods::find($goodsId)) {
            if (Goods::destroy($goodsId)) {
                return responseSuccess('商品删除成功');
            }

            return responseError('商品删除失败');
        }

        return responseError('未找到该商品信息');
    }

    public function goodsCreate(Request $request)
    {

        $errorMsg   = '';
        $success    = true;
        $postMethod = false;
        if ($request->method() == 'POST') {
            $postMethod = true;
            if ($request->get('name') && $request->get('price') && $request->file('goods-pic')) {
                $goodsPic     = $request->file('goods-pic');
                $goodsPicName = date('Y-m-d-H-i-s').'.jpg';
                $uploadResult = \Storage::disk('uploads')->put($goodsPicName,
                    file_get_contents($goodsPic->getRealPath()));
                if ($uploadResult) {
                    $goodsModel              = new Goods();
                    $goodsModel->name        = $request->get('name');
                    $goodsModel->price       = $request->get('price', 0);
                    $goodsModel->img         = 'uploads/'.$goodsPicName;
                    $goodsModel->description = $request->get('description');
                    if ($goodsModel->save()) {
                        $success = true;

                        return view('goods.create', compact('errorMsg', 'success', 'postMethod'));
                    } else {
                        $success  = false;
                        $errorMsg = '商品信息保存失败';
                    }
                }
            }
            $success  = false;
            $errorMsg = '商品信息不全';
        }

        return view('goods.create');
    }

    public function goodsSave(AddGoodsRequest $request)
    {
        $goodsModel = new Goods();

        $goodsModel->name           = $request->get('name');
        $goodsModel->price          = $request->get('price', 0);
        $goodsModel->store_num      = $request->get('storeNum', 0);
        $goodsModel->bar_code       = $request->get('barCode');
        $goodsModel->supply_company = $request->get('supplyCompany');
        $goodsModel->img            = $request->get('goodsImg');
        $goodsModel->description    = $request->get('description');
        if ($goodsModel->save()) {
            return responseSuccess('新商品添加成功');
        }

        return responseSuccess('新商品添加失败');
    }

    public function goodsUpdate($goodsId = 0, AddGoodsRequest $request)
    {
        if ( ! $goodsModel = Goods::find($goodsId)) {
            return responseError('未找到改商品信息');
        }

        $goodsModel->name           = $request->get('name');
        $goodsModel->price          = $request->get('price', 0);
        $goodsModel->store_num      = $request->get('storeNum', 0);
        $goodsModel->bar_code       = $request->get('barCode');
        $goodsModel->supply_company = $request->get('supplyCompany');
        $goodsModel->img            = $request->get('goodsImg');
        $goodsModel->description    = $request->get('description');
        if ($goodsModel->save()) {
            return responseSuccess('商品信息保存成功');
        }

        return responseSuccess('商品信息保存失败');
    }

    public function goodsEdit($goodsId, Request $request)
    {
        $errorMsg   = '';
        $success    = true;
        $postMethod = false;

        $goods = Goods::find($goodsId);
        if ($request->method() == 'POST') {
            $postMethod = true;

            $goodsModel              = new Goods();
            $goodsModel->name        = $request->get('name');
            $goodsModel->price       = $request->get('price', 0);
            $goodsModel->description = $request->get('description');

            if ($request->get('name') && $request->get('price') && ( $request->file('goods-pic') || $request->get('origin-goods-pic') )) {
                if ($request->file('goods-pic')) {
                    $goodsPic        = $request->file('goods-pic');
                    $goodsPicName    = date('Y-m-d-H-i-s').'.jpg';
                    $uploadResult    = \Storage::disk('uploads')->put($goodsPicName,
                        file_get_contents($goodsPic->getRealPath()));
                    $goodsModel->img = 'uploads/'.$goodsPicName;
                } else {
                    $goodsModel->img = $request->get('origin-goods-pic');
                }

                if ($goodsModel->save()) {
                    $success = true;

                    return view('goods.create', compact('errorMsg', 'success', 'postMethod', 'goods'));
                } else {
                    $success  = false;
                    $errorMsg = '商品信息保存失败';
                }
            }
            $success  = false;
            $errorMsg = '商品信息不全';
        }

        return view('goods.edit', compact('errorMsg', 'success', 'postMethod', 'goods'));
    }

    public function goodsInfo($id, Request $request)
    {
        $goodsCollection = Goods::find($id);

        return view('goods.info', compact('goodsCollection'));
    }

}