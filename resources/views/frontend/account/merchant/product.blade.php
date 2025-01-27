@extends('frontend.app')


@section('scripts')
    <script>
        $(document).ready(function () {

            if($('#choice_mode_single').val() === 'single'){
                $('#allow_quantity_selector_block').hide();
            }
            $('.choice_mode').change(function() {
                let val = $(this).val();
                if(val == 'single') {
                    $('#allow_quantity_selector_block').hide();
                    $('#allow_quantity_selector').prop("checked", false);
                }else if(val == 'multiple') {
                    $('#allow_quantity_selector_block').show();
                }
            });

            function pickerFill(obj) {
                let name = $(obj).data('groupname');
                let groupid = $(obj).data('groupid');
                let groupitemid = $(obj).data('groupitemid');
                let itemname = $(obj).data('itemname');
                let itemprice = $(obj).data('itemprice');

                $('#mutator_group_item_title').html(name);
                $('#product_mutator_group_id').val(groupid);
                $('#product_mutator_group_id').val(groupid);

                $('#mutator_group_item_name').val(itemname);
                $('#mutator_group_item_price').val(itemprice);
                $('#product_mutator_group_item_id').val(groupitemid);
            }

            $('#mutator_group_item_modal').on('shown.bs.modal', function (e) {
                pickerFill(e.relatedTarget);
            })

            $('#delete_group_modal').on('shown.bs.modal', function (e) {
                let obj = e.relatedTarget;
                let name = $(obj).data('groupname');
                let groupid = $(obj).data('groupid');
                $('#delete_mutator_group_title').html(name);
                $('#delete_product_mutator_group_id').val(groupid);
            })
            $('#delete_group_item_modal').on('shown.bs.modal', function (e) {
                let obj = e.relatedTarget;
                let name = $(obj).data('groupitemname');
                let groupid = $(obj).data('groupitemid');
                $('#delete_mutator_group_item_title').html(name);
                $('#delete_product_mutator_group_item_id').val(groupid);
            })
        });
    </script>
@endsection

@section('content')

    <div id="mutator_group_modal" class="header-cate-model main-theme-model modal" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Add New Variation</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-address-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.merchant.product.mutator.group.store') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        @csrf
                                        <div class="form-group">
                                            <div class="product-radio">
                                                <ul class="product-now">
                                                    <li>
                                                        <input type="radio" id="choice_mode_single" name="choice_mode" value="single" class="choice_mode" checked>
                                                        <label for="choice_mode_single">Single Selection</label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="choice_mode_multiple" name="choice_mode"  class="choice_mode" value="multiple">
                                                        <label for="choice_mode_multiple">Multiple Selection</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Name*</label>
                                                        <input id="name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'Size', 'Upsize' or 'Topping'" class="form-control input-md" required="">
                                                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12" id="allow_quantity_selector_block">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="allow_quantity_selector" name="allow_quantity_selector" value="1">
                                                        <label class="form-check-label" for="allow_quantity_selector">This variation allows quantity selection</label>
                                                        <p class="p-0 m-0"><small>E.g: Allows adding 5 extra Cheese slices</small></p>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group mb-0">
                                                        <div class="address-btns text-center">
                                                            <button class="next-btn16 hover-btn mx-auto mt-3 mb-3">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-1 mt-2"><small><strong>Single Selection:</strong> The customer can choose only one value</small></p>
                                        <p><small><strong>Multiple Selection:</strong> The customer can choose one or more value</small></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mutator_group_item_modal" class="header-cate-model main-theme-model modal" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>Add New Item for <span id="mutator_group_item_title"></span></h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-address-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.merchant.product.mutator.group.item.store') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="product_mutator_group_id" id="product_mutator_group_id" value="">
                                        <input type="hidden" name="product_mutator_group_item_id" id="product_mutator_group_item_id" value="">
                                        @csrf
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Name*</label>
                                                        <input id="mutator_group_item_name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'Regular', 'Big' or 'Extra Big'" class="form-control input-md" required="">
                                                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Extra Price*</label>
                                                        <input id="mutator_group_item_price" name="extra_price" value="{{ old('extra_price') }}" type="number" min="0" step="0.01" placeholder="E.g. '3', '7.25' or '12'" class="form-control input-md">
                                                        @error('extra_price')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group mb-0">
                                                        <div class="address-btns text-center">
                                                            <button class="next-btn16 hover-btn mx-auto mt-3 mb-3">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete_group_modal" class="header-cate-model main-theme-model modal" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>You are about to remove "<span id="delete_mutator_group_title"></span>" variation</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-address-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.merchant.product.mutator.group.delete') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="product_mutator_group_id" id="delete_product_mutator_group_id" value="">
                                        @csrf
                                        @method('delete')
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <p>By removing this item, you will no longer be able to restore it</p>
                                                    <p><strong>This action cannot be undone</strong></p>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group mb-0 text-right pt-4">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal" aria-label="Close">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Im sure, delete it</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete_group_item_modal" class="header-cate-model main-theme-model modal" tabindex="-1" role="dialog" aria-modal="false">
        <div class="modal-dialog category-area" role="document">
            <div class="category-area-inner">
                <div class="modal-header">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="uil uil-multiply"></i>
                    </button>
                </div>
                <div class="category-model-content modal-content">
                    <div class="cate-header">
                        <h4>You are about to remove "<span id="delete_mutator_group_item_title"></span>" item</h4>
                    </div>
                    <div class="add-address-form">
                        <div class="checout-address-step">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ route('account.merchant.product.mutator.group.item.delete') }}" method="POST">
                                        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="product_mutator_group_item_id" id="delete_product_mutator_group_item_id" value="">
                                        @csrf
                                        @method('delete')
                                        <div class="address-fieldset">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <p>By removing this item, you will no longer be able to restore it</p>
                                                    <p><strong>This action cannot be undone</strong></p>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group mb-0 text-right pt-4">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal" aria-label="Close">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Im sure, delete it</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        @include('frontend.shared.alert')

        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('frontend.account.merchant.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <a href="{{ route('account.merchant.shop', $merchant->id) }}"><h4><i class="uil uil-arrow-circle-left"></i>Back to products</h4></a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">

                                        <div class="pdpt-bg">
                                            <div class="pdpt-title">
                                                <h4>General Product Information</h4>
                                            </div>
                                            <form method="POST" action="{{ route('account.merchant.product.store', ['merchant_id' => $merchant->id]) }}" enctype="multipart/form-data">
                                                <input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchant->id }}" readonly required>
                                                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}" readonly required>
                                                @csrf
                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Name *</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" autocomplete="name" autofocus placeholder="Product Name" required>
                                                                        </div>
                                                                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Category </label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <select class="form-control" name="private_category_id" id="private_category_id">
                                                                                    <option value="" selected>No Category</option>
                                                                                    @foreach($merchant->privateCategories as $privateCategoryItem)
                                                                                        <option value="{{ $privateCategoryItem->id }}" @if((int)old('private_category_id', $product->private_category_id) === (int)$privateCategoryItem->id) selected @endif>{{ $privateCategoryItem->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        @error('private_category_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Price*</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="price" type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" placeholder="Product Price" required>
                                                                        </div>
                                                                        @error('price')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Discount </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="discount" type="number" step="0.01" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{ old('discount', $product->discount) }}" placeholder="Product Discount">
                                                                        </div>
                                                                        @error('discount')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <hr>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1 mb-2">
                                                                        <img src="{{ $product->image ? asset($product->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $product->name }}" class="img-fluid" width="150">
                                                                        <label for="attachment" class="control-label">@if($product->image )Edit product picture @else Upload product picture @endif</label>
                                                                    </div>
                                                                    <input type="file" class="form-control-file" name="attachment" id="attachment">
                                                                    @error('attachment')<span class="invalid-feedback pt-2" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-3 pt-3">
                                                                        <div class="field">
                                                                            <label class="control-label">Product Description</label>
                                                                            <textarea rows="6" class="form-control" id="description" name="description" placeholder="Please describe the product">{{ old('description', $product->description) }}</textarea>
                                                                        </div>
                                                                        @error('description')<span class="invalid-feedback pt-2" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-12 text-center mb-4">
                                                                    <button class="next-btn16 hover-btn mt-3" type="submit" data-btntext-sending="Sending...">Save</button>
                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>
                                            <div class="pdpt-title">
                                                <div class="d-flex flex-row align-items-center">
                                                    <h4 class="mb-0">Product Variations</h4>
                                                    @if(count($product->mutatorGroups) > 0)
                                                        <a href="#" class="btn btn-sm btn-primary ml-md-3 ml-0" data-toggle="modal" data-target="#mutator_group_modal">Add new variation</a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">
                                                                @if($product->id)
                                                                    @if(count($product->mutatorGroups) == 0)
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label"> Your product has no variations</label>
                                                                                <a href="#" class="btn btn-sm btn-primary ml-md-3 ml-0" data-toggle="modal" data-target="#mutator_group_modal">Add one variation</a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        @foreach($product->mutatorGroups as $mutatorGroupItem)

                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div class="pdpt-bg">
                                                                                    <div class="pdpt-title">
                                                                                        <div class="d-flex flex-row align-items-center justify-content-between">
                                                                                            <h4 class="mb-0">{{ $mutatorGroupItem->name }}</h4>
                                                                                            <div>
                                                                                                @if(count($mutatorGroupItem->mutators) > 0)
                                                                                                    <a href="#" class="btn btn-sm btn-info ml-md-3 ml-0"
                                                                                                       data-toggle="modal"
                                                                                                       data-target="#mutator_group_item_modal"
                                                                                                       data-groupname="{{ $mutatorGroupItem->name }}"
                                                                                                       data-groupid="{{ $mutatorGroupItem->id }}"
                                                                                                       data-groupchoise="{{ $mutatorGroupItem->choice_mode }}"
                                                                                                       data-groupitemid=""
                                                                                                       data-itemname=""
                                                                                                       data-itemprice=""
                                                                                                    >Add {{ $mutatorGroupItem->name }}</a>
                                                                                                @endif
                                                                                                <a href="#" class="btn btn-sm btn-danger ml-md-3 ml-0"
                                                                                                   data-toggle="modal"
                                                                                                   data-target="#delete_group_modal"
                                                                                                   data-groupname="{{ $mutatorGroupItem->name }}"
                                                                                                   data-groupid="{{ $mutatorGroupItem->id }}"
                                                                                                   title="Delete"
                                                                                                ><i class="uil uil-trash"></i></a>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="active-offers-body">
                                                                                        @if(count($mutatorGroupItem->mutators) == 0)
                                                                                            <div class="form-group text-right">
                                                                                                <label class="control-label"> You have no {{ $mutatorGroupItem->name }}</label>
                                                                                                <a href="#" class="btn btn-sm btn-info ml-md-3 ml-0"
                                                                                                   data-toggle="modal"
                                                                                                   data-target="#mutator_group_item_modal"
                                                                                                   data-groupname="{{ $mutatorGroupItem->name }}"
                                                                                                   data-groupid="{{ $mutatorGroupItem->id }}"
                                                                                                   data-groupchoise="{{ $mutatorGroupItem->choice_mode }}"
                                                                                                   data-groupitemid=""
                                                                                                   data-itemname=""
                                                                                                   data-itemprice=""
                                                                                                >Add {{ $mutatorGroupItem->name }}</a>
                                                                                            </div>
                                                                                        @else
                                                                                        <div class="table-responsive mb-5">
                                                                                            <table class="table ucp-table earning__table">
                                                                                                <thead class="thead-s">
                                                                                                <tr>
                                                                                                    <th scope="col">Name</th>
                                                                                                    <th scope="col">Price</th>
                                                                                                    <th scope="col">Allows quantity?</th>
                                                                                                    <th scope="col">Selection Mode</th>
                                                                                                    <th scope="col" class="text-right">Actions</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                @foreach($mutatorGroupItem->mutators as $mutatorGroupItemMutatorItem)
                                                                                                <tr>
                                                                                                    <td><strong>{{ $mutatorGroupItemMutatorItem->name }}</strong></td>
                                                                                                    <td>@if($mutatorGroupItemMutatorItem->extra_price > 0) + ${{ $mutatorGroupItemMutatorItem->extra_price }} @else 0 @endif</td>
                                                                                                    <td>@if($mutatorGroupItem->allow_quantity_selector) YES @else NO @endif</td>
                                                                                                    <td>{{ ucfirst($mutatorGroupItem->choice_mode) }}</td>
                                                                                                    <td class="text-right">
                                                                                                        <a href="#" class=""
                                                                                                           data-toggle="modal"
                                                                                                           data-target="#mutator_group_item_modal"
                                                                                                           data-groupname="{{ $mutatorGroupItem->name }}"
                                                                                                           data-groupid="{{ $mutatorGroupItem->id }}"
                                                                                                           data-groupchoise="{{ $mutatorGroupItem->choice_mode }}"
                                                                                                           data-groupitemid="{{ $mutatorGroupItemMutatorItem->id }}"
                                                                                                           data-itemname="{{ $mutatorGroupItemMutatorItem->name }}"
                                                                                                           data-itemprice="{{ $mutatorGroupItemMutatorItem->extra_price }}"
                                                                                                        ><i class="uil uil-edit"></i></a>

                                                                                                        <a href="#" class=""
                                                                                                           data-toggle="modal"
                                                                                                           data-target="#delete_group_item_modal"
                                                                                                           data-groupitemname="{{ $mutatorGroupItemMutatorItem->name }}"
                                                                                                           data-groupitemid="{{ $mutatorGroupItemMutatorItem->id }}"
                                                                                                           title="Delete"
                                                                                                        ><i class="uil uil-trash"></i></a>

                                                                                                    </td>
                                                                                                </tr>
                                                                                                @endforeach
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        @endforeach
                                                                    @endif
                                                                @else
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p class="black">
                                                                                <small>Variations enable a customer to select different combinations of products,
                                                                                    for example a Hamburger product, may contain the "Size" variation with
                                                                                    the unique selection values "Regular", "Large", "Extra Large",
                                                                                    or a multiple-choice variation called "Extras" with
                                                                                    "Cheese", "Ham", "Onion" values.</small>
                                                                            </p>
                                                                            <label class="control-label pt-3" style="font-size: 15px">You must save your product before creating variations</label>
                                                                        </div>

                                                                    </div>
                                                                @endif

{{--                                                                <div class="col-md-12">--}}
{{--                                                                    <div class="form-group mt-1">--}}
{{--                                                                        <label class="control-label">Contact Email </label>--}}
{{--                                                                        <div class="form-group pos_rel">--}}
{{--                                                                            <input id="contact_email" type="email" class="form-control lgn_input @error('contact_email') is-invalid @enderror" name="contact_email" value="{{ old('contact_email', $merchant->contact_email) }}" placeholder="Contact Email">--}}
{{--                                                                            <i class="uil uil-envelope lgn_icon"></i>--}}
{{--                                                                        </div>--}}
{{--                                                                        @error('contact_email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}

{{--                                                                <div class="col-md-12">--}}
{{--                                                                    <div class="form-group mt-1">--}}
{{--                                                                        <label class="control-label">Contact Phone </label>--}}
{{--                                                                        <div class="form-group pos_rel">--}}
{{--                                                                            <input id="contact_phone" type="text" class="form-control lgn_input @error('contact_phone') is-invalid @enderror" name="contact_phone" value="{{ old('contact_phone', $merchant->contact_phone) }}" placeholder="Contact Phone">--}}
{{--                                                                            <i class="uil uil-phone lgn_icon"></i>--}}
{{--                                                                        </div>--}}
{{--                                                                        @error('contact_phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
