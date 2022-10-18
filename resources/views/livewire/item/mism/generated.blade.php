
@php
  $filename = "MISM_" .str_replace(",", "", $generatedDate) .'.xls';
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=" . $filename);  
  header("Pragma: no-cache"); 
  header("Expires: 0");
@endphp
<!DOCTYPE html>
<html>
<head>
  <title>Monthly Inventory Supplies and Materials - {{ $generatedDate }}</title>
  <style type="text/css">
    @media print {
      @page {
        size: legal landscape;
        margin: 0;
      }
      .header {
        font-size: 12px !important;
        text-align: center !important;
      }
      h4 {
        padding: 0;
      }
      .text-center {
        text-align: center !important;
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
      td.text-right {
        text-align: right !important;
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
      .slc {
        width: 300px !important;
      }

      .newpage {
        clear: both;
        page-break-before: always;
      }
    }
    .header {
      font-size: 12px !important;
      text-align: center !important;
    }
    h4 {
      padding: 0;
      margin: 0;
    }
    .text-center {
      text-align: center !important;
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
      font-size: 12x !important;
    }
    table td {
      border: 1px solid #000;
      padding-right: 5px;
    }
    table td.shrink {
      white-space:nowrap
    }
    td.text-right {
      text-align: right !important;
    }
    .trese {
      font-size: 12px !important;
    }
    .ten {
      font-size: 11px !important;
    }
    .footerLabel {
      text-align: left !important;
    }
    .slc {
      width: 300px !important;
    }

    .newpage {
      margin-top: 25px !important; 
      clear: both;
      page-break-before: always;
    }
  </style>
</head>

<body>
  <div class="header">
    <h3>MONTHLY INVENTORY OF SUPPLIES AND MATERIALS<br>
      <small>Centralized Storage</small><br>
      <small><i>as of {{ $generatedDate }}</i></small></h3>
    </div>
    <table class="table table-hover" cellspacing="0" role="grid" border="1">
      <thead>
        <colgroup span="2"></colgroup>
        <colgroup span="2"></colgroup>
        <tr>
          <td rowspan="2" class="text-center">ARTICLE</td>
          <td rowspan="2" class="text-center">DESCRIPTION</td>
          <td rowspan="2" class="text-center">REFERENCE</td>
          <td rowspan="2" class="text-center">STOCK NUMBER</td>
          <td rowspan="2" class="text-center">UNIT<br>OF<br>ISSUE</td>
          <td rowspan="2" class="text-center">UNIT VALUE</td>
          <th colspan="2" scope="colgroup">BEGINNING BALANCE</th>
          <th colspan="2" scope="colgroup">DELIVERY</th>
          <th colspan="2" scope="colgroup">S.S.M.I</th>
          <th colspan="2" scope="colgroup">ENDING BALANCE</th>
        </tr>
        <tr>
          <th scope="col">QTY</th>
          <th scope="col">AMOUNT</th>
          <th scope="col">QTY</th>
          <th scope="col">AMOUNT</th>
          <th scope="col">QTY</th>
          <th scope="col">AMOUNT</th>
          <th scope="col">QTY</th>
          <th scope="col">AMOUNT</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($mism as $item => $val)
            <tr>
                <td>{{ $val['article'] }}</td>
                <td>{{ $val['description'] }}</td>
                <td>{{ $val['reference'] }}</td>
                <td>{{ $val['stock_number'] }}</td>
                <td>{{ $val['unit'] }}</td>
                <td>{{ number_format($val['value'], 2) }}</td>
                
                <td>{{ ($val['beginningQty'] != 0) ? number_format($val['beginningQty']) : ""  }}</td>
                <td>{{ ($val['beginningAmount'] != 0) ? number_format($val['beginningAmount'], 2) : "" }}</td>

                <td>{{ ($val['deliveryQty'] != 0) ? number_format($val['deliveryQty']) : "" }}</td>
                <td>{{ ($val['deliveryAmount'] != 0) ? number_format($val['deliveryAmount'], 2) : "" }}</td>

                <td>{{ ($val['SSMIQty'] != 0) ? number_format($val['SSMIQty']): "" }}</td>
                <td>{{ ($val['SSMIAmount'] != 0) ? number_format($val['SSMIAmount'], 2): "" }}</td>

                <td>{{ ($val['EndingQty'] != 0) ? number_format($val['EndingQty']): "0" }}</td>
                <td>{{ ($val['EndingAmount'] != 0) ? number_format($val['EndingAmount'], 2): "0" }}</td>
            </tr>
        @endforeach
      <tr>
        <td class="text-right" colspan="7"><b><i>GRAND TOTAL</i></b></td>
        <td class="text-right"><b>{{ number_format($grand['BeginningAmount'], 2) }}</b></td>

        <td></td>
        <td class="text-right"><b>{{ ($grand['DeliveryAmount'] !=0 ) ?  number_format($grand['DeliveryAmount'], 2) : ""}}</b></td>

        <td></td>
        <td class="text-right"><b>{{ ($grand['SSMIAmount'] != 0) ? number_format($grand['SSMIAmount'], 2) : "" }}</b></td>
        
        <td></td>
        <td class="text-right"><b>{{ ($grand['EndingAmount'] != 0) ? number_format($grand['EndingAmount'], 2) : "" }}</b></td>
      </tr>
    </tbody>
  </table>
  <br>
  <table class="table" border="1">
    <tr>
      <td style="padding-left: 50px;border: 0;">Prepared By:</td>
      <td style="padding-left: 50px;border: 0;">Noted By:</td>
      <td style="padding-left: 50px;border: 0;">Certified Correct:</td>
      <td style="padding-left: 50px;border: 0;">Approved By:</td>
    </tr>
    <tr>
      <td class="text-center" style="padding-top: 30px;border: 0;">
        <b>{{ strtoupper(Auth()->user()->name) }}</b><br>
        <?php echo nl2br(Auth()->user()->position) ?>
      </td>
      <td class="text-center" style="padding-top: 30px;border: 0;">
        <b>{{ strtoupper($noting->name) }}</b><br>
        <?php echo nl2br($noting->designation) ?>
      </td>
      <td class="text-center" style="padding-top: 30px;border: 0;">
        <b>{{ strtoupper($certify->name) }}</b><br>
        <?php echo nl2br($certify->designation) ?>
      </td>
      <td class="text-center" style="padding-top: 30px;border: 0;">
        <b>{{ strtoupper($approving->name) }}</b><br>
        <?php echo nl2br($approving->designation) ?>
      </td>
    </tr>
  </table>


</body>
</html>