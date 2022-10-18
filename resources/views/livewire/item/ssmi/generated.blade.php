
@php
$filename = "SSMI" .str_replace(",", "", $generatedDate) .'.xls';
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=" . $filename);  
header("Pragma: no-cache"); 
header("Expires: 0");
@endphp
<!DOCTYPE html>
<html>
<head>
<title>Summary of Supplies and Materials Issued - {{ $generatedDate }}</title>
<style type="text/css">
  @media print {

    .header {
      width: 100%;
      font-size: 12px !important;
      text-align: center !important;
    }
    h4 {
      padding: 0;
    }
    .qwe {
      line-height: 20px !important;
        margin-top: 50px !important;
        padding: 0 !important;
    }
    table {
      border-collapse: collapse;
      width:100%;
    }
    th {
      font-size: 13px !important;
      font-weight: 150;
    }
    th.bold {
      font-weight: 600;
    }
    td {
      font-size: 12px !important;
    }
    table td {
      border: 1px solid #000;
      /* padding-right: 5px; */
    }
    table td.shrink {
      white-space:nowrap
    }
    .trese {
      font-size: 10px !important;
    }
    .ten {
      font-size: 11px !important;
    }
    .footerLabel {
      text-align: left !important;
    }
    .text-center {
      text-align: center !important;
    }
    .text-right {
      text-align: right !important;
    }
    .text-left {
      text-align: left !important;
    }
    .slc {
      width: 300px !important;
    }

    .newpage {
      clear: both;
      page-break-before: always;
    }


    @page {
      size: legal landscape;
      margin: 0;
    }
  }
 
</style>
</head>
<?php
$officeRIS = [];
$colspan = 7 + count($offices);
?>
<body>
<div class="header">
  <table class="table">
    <tr class="text-center">
      <th class="text-center" colspan="{{ $colspan }}"><h3>Summary of Supplies and Materials Issued<br>
        <small>Centralized Storage</small><br>
        <small><i>as of {{ $generatedDate }}</i></small></h3></th>
    </tr>
  </table>

  </div>
  <table class="table table-hover" cellspacing="0" role="grid" border="1">
    <thead>
      <colgroup span="4"></colgroup>
      <tr>
        <td colspan="4"></td>
        <th colspan="{{ count($offices) }}" scope="colgroup"><i><small><b>REQUISITION AND ISSUE SLIP</b></small></i></th>
      </tr>
      <tr>
        <th rowspan="2" class="text-center"><b>Reference</b></th>
        <th rowspan="2" class="text-center"><b>Stock No.</b></th>
        <th rowspan="2" class="text-center"><b>Item Description</b></th>
        <th rowspan="2" class="text-center"><b>Unit of Measure</b></th>

            @foreach ($offices as $key => $value)
                <td><br></td>
            @endforeach
        <th class="text-center" rowspan="2"><b>Total<br>Quantity<br>Issued</b></th>
        <th class="text-center" rowspan="2"><b>Unit Cost</b></th>
        <th class="text-center" rowspan="2"><b>Total Cost</b></th>
      </tr>
      <tr>
        @foreach ($offices as $key => $value)

        @php
          $office = array(
            'office' => $value['office'],
            'totalCostPerOffice' => 0
          );
          $officeRIS[$value['ris_no']][] = $office;
        @endphp    
          <td style="text-align: center !important;"><b>{{$value['office']}}</b></td>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <?php
        $grandCost = 0;
      ?>
      @foreach ($ssmi as $val => $item)
      <?php
        $totalQuantity = 0;  
      ?>
          <tr>
            <td class="text-left">{{ $item['reference'] }}</td>
            <td class="text-left">{{ $item['stock_number'] }}</td>
            <td class="text-left">{{ $item['description'] }}</td>
            <td class="text-center">{{ $item['unit'] }}</td>

            @foreach ($offices as $key => $value)

              <?php
                $quantity = 0;
                $ris = $results[$item['reference_id']];
                
                $item_ = $ris[$value['ris_no']];

                if ($item_[0]['office'] == $value['office']) {
                  $quantity = $item_[0]['quantity'];
                  $totalQuantity += $quantity;
                  $officeRIS[$value['ris_no']][0]['totalCostPerOffice'] += ($quantity * $item['unitCost']);
                }

              ?>

              <td class="text-right">{{ ($quantity != 0) ? number_format($item_[0]['quantity']) : "" }}</td>

            @endforeach

            <td class="text-right">{{ number_format($totalQuantity) }}</td>
            <td class="text-right">{{ number_format($item['unitCost'], 2) }}</td>
            @php
              $grandCost += $totalQuantity * $item['unitCost'];
            @endphp
            <td class="text-right">{{ number_format($totalQuantity * $item['unitCost'], 2) }}</td>
          </tr>
      @endforeach

      <tr>
        <td colspan="4"></td>
        @foreach ($officeRIS as $key => $value)
          <td class="text-center">{{ number_format($value[0]['totalCostPerOffice'],2) }}</td>
        @endforeach
        <td colspan="2"></td>
        <td  class="text-right">{{ number_format($grandCost, 2) }}</td>
      </tr>
  </tbody>
</table>
<table class="table" width="100%" border="1" style="outline: thin solid">
  <thead>
    <tr>
      <th style="text-align: left !important;" colspan="3">Prepared By:</th>
      <th style="text-align: left !important;" colspan="3">Noted by:</th>
      <th style="text-align: left !important;" colspan="3">Certified Correct:</th>
      <th style="text-align: left !important;" colspan="3">Approved by:</th>
      <th style="text-align: left !important;" colspan="3">Posted in the SLC by /dated:</th>
    </tr>
  </thead>
  <tbody>
    <tr style="text-align: center !important;">
      <td style="border: 1;" colspan="3" class="text-center">
        <p style="margin-top: 50px !important;">
          <b>{{ strtoupper(Auth()->user()->name) }}</b><br>
          <?php echo nl2br(Auth()->user()->position) ?>
        </p>
      </td>
      <td style="border: 1;" colspan="3" class="text-center"> 
        <p style="margin-top: 50px !important;">
          <b>{{ strtoupper($noting->name) }}</b><br>
          <?php echo nl2br($noting->designation) ?>
        </p>
      </td>
      <td style="border: 1;" colspan="3" class="text-center"> 
        <p style="margin-top: 50px !important;">
          <b>{{ strtoupper($certifying->name) }}</b><br>
          <?php echo nl2br($certifying->designation) ?>
        </p>
      </td>
      <td style="border: 1;" colspan="3" class="text-center"> 
          <p style="margin-top: 50px !important;">
            <b>{{ strtoupper($approving->name) }}</b><br>
            <?php echo nl2br($approving->designation) ?>
          </p>
        </td>
      <td style="border: 1;" colspan="3" class="text-center"> 
        <p style="margin-top: 50px !important;"><b></b></p>
      </td>
    </tr>
  </tbody>
</table>


</body>
</html>