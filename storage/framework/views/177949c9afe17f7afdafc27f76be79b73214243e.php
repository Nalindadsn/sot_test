<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="<?php echo e(url('public/logo', $general_setting->site_logo)); ?>" />
    <title><?php echo e($general_setting->site_title); ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {padding: 7px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media  print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            @page  { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; }
            tbody::after {
                content: '';
                display: block;
                page-break-after: always;
                page-break-inside: always;
                page-break-before: avoid;        
            }
        }
    </style>
  </head>
<body>

<div style="margin:0 auto">

    <?php if(preg_match('~[0-9]~', url()->previous())): ?>
        <?php $url = '../../pos'; ?>
    <?php else: ?>
        <?php $url = url()->previous(); ?>
    <?php endif; ?>
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="<?php echo e($url); ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> <?php echo e(trans('file.Back')); ?></a> </td>
                <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> <?php echo e(trans('file.Print')); ?></button></td>
            </tr>
        </table>
        <br>
    </div>
        
    <div id="receipt-data">
        <table>
            <tr>
                <td style="width: 20%">
                    <?php if($general_setting->site_logo): ?>
                        <img src="<?php echo e(url('public/logo', $general_setting->site_logo)); ?>" height="42" width="50" style="margin:10px 0;filter: brightness(0);">
                    <?php endif; ?>
                </td>
                <td style="width: 40%">
                    <h2><?php echo e($lims_biller_data->company_name); ?></h2>

                    <p><?php echo e(trans('file.Address')); ?>: <?php echo e($lims_warehouse_data->address); ?>

                        <br><?php echo e(trans('file.Phone Number')); ?>: <?php echo e($lims_warehouse_data->phone); ?>

                    </p>
                </td>
                <td style="width: 40%;align-content: right" align="right">
                    <table style="border: black 1px solid">
                        <tr>
                            <h3 style="font-size: 17px"><b>Invoice</b></h3>
                        </tr>
                        <tr>
                            <td style="width: 50%;border: black 1px solid" align="center">
                                <h2>Invoice Date</h2>
                            </td>
                            <td style="width: 50%;border: black 1px solid" align="center">
                                <h2>Invoice #</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;border: black 1px solid" align="center">
                                <h2><?php echo e(\Carbon\Carbon::parse($lims_sale_data->created_at)->format('d/m/Y')); ?></h2>
                            </td>
                            <td style="width: 50%;border: black 1px solid" align="center">
                                <h2><?php echo e($lims_sale_data->reference_no); ?></h2>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>



        <table>
            <tr>
                <td>
                    <table>
                        <tr style="border: black 1px solid">
                            <td><b>Bill To</b></td>
                        </tr>
                        <tr style="border: black 1px solid;min-height: 50px">
                            <td></td>
                        </tr>
                    </table>

                </td>

                <td style="width: 4%">
                </td>

                <td>
                    <table>
                        <tr style="border: black 1px solid">
                            <td><b>Ship To</b></td>
                        </tr>
                        <tr style="border: black 1px solid;min-height: 50px">
                            <td> </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>



        <table class="table-data">
            <thead>
            <tr>
                <th style="min-width:300px!important;width: 60%;border: 1px solid black;">Item</th>
                
                <th style="border: 1px solid black">QTY</th>
                <th style="min-width:80px!important;width: 18%;border: 1px solid black">Rate</th>
                <th style="min-width:80px!important;width: 18%;border: 1px solid black">Amount</th>
            </tr>
            </thead>
            <tbody>
                <?php $total_product_tax = 0;?>
                <?php $__currentLoopData = $lims_product_sale_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product_sale_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                    if($product_sale_data->variant_id) {
                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                        $product_name = $lims_product_data->name.' ['.$variant_data->name.']';
                    }
                    elseif($product_sale_data->product_batch_id) {
                        $product_batch_data = \App\ProductBatch::select('batch_no')->find($product_sale_data->product_batch_id);
                        $product_name = $lims_product_data->name.' ['.trans("file.Batch No").':'.$product_batch_data->batch_no.']';
                    }
                    else
                        $product_name = $lims_product_data->name;
                ?>


                <tr>
                    <td style="width: 60%;border: 1px solid black"><?php echo e($product_name); ?></td>

                    <td style="width: 5px;border: 1px solid black" align="center"><?php echo e($product_sale_data->qty); ?></td>
                    <td style="min-width:10%;width: 18%;border: 1px solid black" align="right"> <?php echo e(number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', '')); ?>


                        <?php if($product_sale_data->tax_rate): ?>
                            <?php $total_product_tax += $product_sale_data->tax ?>
                            [<?php echo e(trans('file.Tax')); ?> (<?php echo e($product_sale_data->tax_rate); ?>%): <?php echo e($product_sale_data->tax); ?>]
                        <?php endif; ?>
                    </td>
                    <td style="width: 18%;border: 1px solid black" align="right"><?php echo e(number_format((float)$product_sale_data->total, 2, '.', '')); ?></td>
                </tr>




                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
            <!-- </tfoot> -->
        </table>

        <br>
        <table>
            <tbody>
            <!-- <tfoot> -->
                <tr>
                    <td style="width: 60%;">
                        <table>

                            <tr>
                                <td style="text-align:left;width: 90%; border: 1px black solid;border-radius: 5px"><b>Please do the payment to;</b>
                                <br>
                                    <b>Account Name :</b> Bensalem US Trading (Pvt) Ltd <br>
                                        <b>Account No :</b> 1000376614 <br>
                                            <b>Bank :</b> Commercial Bank<br>
                                                <b>Branch :</b> World Trade Center

                                </td>
                                <td style="text-align:left;width: 10%;"></td>

                               </tr>

                        </table>
                    </td>
                    <th style="width: 50%;">
                        <table>
                            <tr>
                                <th style="text-align:left;width: 60%;"><?php echo e(trans('file.Total')); ?></th>
                                <th style="text-align:right;width: 40%;"><?php echo e(number_format((float)$lims_sale_data->total_price, 2, '.', '')); ?></th>
                            </tr>

                            <?php if($general_setting->invoice_format == 'gst' && $general_setting->state == 1): ?>
                                <tr>
                                    <td  style="text-align:left;width: 60%;">IGST</td>
                                    <td style="text-align:right;width: 40%;"><?php echo e(number_format((float)$total_product_tax, 2, '.', '')); ?></td>
                                </tr>
                            <?php elseif($general_setting->invoice_format == 'gst' && $general_setting->state == 2): ?>
                                <tr>
                                    <td  style="text-align:left;width: 60%;">SGST</td>
                                    <td style="text-align:right;width: 40%;"><?php echo e(number_format((float)($total_product_tax / 2), 2, '.', '')); ?></td>
                                </tr>
                                <tr>
                                    <td  style="text-align:left;width: 60%;">CGST</td>
                                    <td style="text-align:right;width: 40%;"><?php echo e(number_format((float)($total_product_tax / 2), 2, '.', '')); ?></td>
                                </tr>
                            <?php endif; ?>

                            <?php if($lims_sale_data->order_tax): ?>
                                <tr>
                                    <th style="text-align:left;width: 60%;" style="text-align:left"><?php echo e(trans('file.Order Tax')); ?></th>
                                    <th style="text-align:right;width: 40%;"><?php echo e(number_format((float)$lims_sale_data->order_tax, 2, '.', '')); ?></th>
                                </tr>
                            <?php endif; ?>
                            <?php if($lims_sale_data->order_discount): ?>
                                <tr>
                                    <th style="text-align:left;width: 60%;" style="text-align:left"><?php echo e(trans('file.Order Discount')); ?></th>
                                    <th style="text-align:right;width: 40%;"><?php echo e(number_format((float)$lims_sale_data->order_discount, 2, '.', '')); ?></th>
                                </tr>
                            <?php endif; ?>
                            <?php if($lims_sale_data->coupon_discount): ?>
                                <tr>
                                    <th style="text-align:left;width: 60%;" style="text-align:left"><?php echo e(trans('file.Coupon Discount')); ?></th>
                                    <th style="text-align:right;width: 40%;"><?php echo e(number_format((float)$lims_sale_data->coupon_discount, 2, '.', '')); ?></th>
                                </tr>
                            <?php endif; ?>
                            <?php if($lims_sale_data->shipping_cost): ?>
                                <tr>
                                    <th style="text-align:left;width: 60%;" style="text-align:left"><?php echo e(trans('file.Shipping Cost')); ?></th>
                                    <th style="text-align:right;width: 40%;"><?php echo e(number_format((float)$lims_sale_data->shipping_cost, 2, '.', '')); ?></th>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th style="text-align:left;width: 60%;" style="text-align:left"><?php echo e(trans('file.grand total')); ?></th>
                                <th style="text-align:right"><?php echo e(number_format((float)$lims_sale_data->grand_total, 2, '.', '')); ?></th>
                            </tr>

                            <tr>
                                <?php if($general_setting->currency_position == 'prefix'): ?>
                                    <th class="centered" colspan="3"><?php echo e(trans('file.In Words')); ?>: <span><?php echo e($currency->code); ?></span> <span><?php echo e(str_replace("-"," ",$numberInWords)); ?></span></th>
                                <?php else: ?>
                                    <th class="centered" colspan="3"><?php echo e(trans('file.In Words')); ?>: <span><?php echo e(str_replace("-"," ",$numberInWords)); ?></span> <span><?php echo e($currency->code); ?></span></th>
                                <?php endif; ?>
                            </tr>



                        </table>
                    </th>
                </tr>
        </tbody>
        <!-- </tfoot> -->
        </table>



                
                    
                    
                    
                
                
                
                    
                    
                    
                
                
                
                    
                    
                    
                
                
                    
                    
                    
                
                
                
                
                    
                    
                    
                
                
                
                
                    
                    
                    
                
                
                
                
                    
                    
                    
                
                
                
                
                    
                    
                    
                
                
                
                    
                    
                    
                


        <br>
        <table>
            <tbody>
                <?php $__currentLoopData = $lims_payment_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="background-color:#ddd;">

                    <td style="padding: 5px;width:30%"><?php echo e(trans('file.Paid By')); ?>: <?php echo e($payment_data->paying_method); ?></td>
                    <td style="padding: 5px;width:40%"><?php echo e(trans('file.Amount')); ?>: <?php echo e(number_format((float)$payment_data->amount, 2, '.', '')); ?></td>
                    <td style="padding: 5px;width:30%"><?php echo e(trans('file.Change')); ?>: <?php echo e(number_format((float)$payment_data->change, 2, '.', '')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="centered" style="width:30%">..........................</td>
                    <td class="centered" style="width:30%">..........................</td>
                    <td class="centered" style="width:30%">..........................</td>
                </tr>

                <tr>
                    <td class="centered" style="width:30%">Checked By</td>
                    <td class="centered" style="width:30%">Authorized By</td>
                    <td class="centered" style="width:30%">Received By</td>
                </tr>

                <tr><td class="centered" colspan="3"><?php echo e(trans('file.Thank you for shopping with us. Please come again')); ?></td></tr>
                <tr hidden>
                    <td class="centered" colspan="3">
                    <?php echo '<img style="margin-top:10px;" src="data:image/png;base64,' . DNS1D::getBarcodePNG($lims_sale_data->reference_no, 'C128') . '" width="300" alt="barcode"   />';?>
                    <br>
                    <?php echo '<img style="margin-top:10px;" src="data:image/png;base64,' . DNS2D::getBarcodePNG($lims_sale_data->reference_no, 'QRCODE') . '" alt="barcode"   />';?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- <div class="centered" style="margin:30px 0 50px">
            <small><?php echo e(trans('file.Invoice Generated By')); ?> <?php echo e($general_setting->site_title); ?>.
            <?php echo e(trans('file.Developed By')); ?> LionCoders</strong></small>
        </div> -->
    </div>
</div>

<script type="text/javascript">
    localStorage.clear();
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
<?php /**PATH /home/ikinzico/system.secretsoftea.lk/development/develop2/resources/views/sale/invoice1.blade.php ENDPATH**/ ?>