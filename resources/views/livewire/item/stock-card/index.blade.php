<!DOCTYPE html>
<html>
<head>
    <title>Stock Card - {{ session()->get('date_from') . ' - ' . session()->get('date_to') }}</title>
    <style type="text/css">
        @media print {
        @page {
          size: legal portrait;
          margin: 0;
        }
        body {
          padding: 30px;
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
          font-size: 11px !important;
          font-weight: 150;
        }
        th.bold {
          font-weight: 600;
        }
        td {
          font-size: 9px !important;
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
          font-size: 8px !important;
        }
        .ten {
          font-size: 9px !important;
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
          font-size: 11px !important;
          font-weight: 150;
        }
        th.bold {
          font-weight: 600;
        }
        td {
          font-size: p9x !important;
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
          font-size: 8px !important;
        }
        .ten {
          font-size: 9px !important;
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
        .padding-left {
          padding-left: 15px;
        }
        body {
          padding: 30px;
        }
    </style>
</head>

<body>
  <table class="table">
    <tr>
      <td colspan="6" class="text-center"><h2>STOCK CARD</h2><h3>LGU - QUEZON CITY GOVERNMENT</h3>
        <small style="float: left;">Date From : {{ $stockCardDate }}</small>
      </td>
    </tr>
    <tr>
      <td width="33%" class="padding-left" colspan="2">Item : <br><h2>{{$items->Article->Classification->classification . ' - ' . $items->Article->article }}</h2></td>
      <td width="33%" class="padding-left" colspan="3">Description<br><h2>{{$items->description . ' - ' . $items->Unit->unit}}</h2></td>
      <td width="33%" class="padding-left" colspan="1">Re-order Point<br><br></td>
    </tr>
  </table>

  

    <table class="table" cellspacing="0" role="grid" border="1">
            <thead>
              <colgroup span="2"></colgroup>
              <colgroup span="2"></colgroup>
              <tr>
                <td rowspan="2" class="text-center">Date</td>
                <td rowspan="2" class="text-center">Reference</td>
                <td rowspan="2" class="text-center">Receipt<br>Qty</td>
                <td colspan="2" class="text-center" scope="colgroup">I s s u a n c e</td>
                <td rowspan="2" class="text-center">Balance<br>Qty</td>
              </tr>
              <tr>
                <td class="text-center" scope="col">Qty</td>
                <td class="text-center" scope="col">Office</td>
              </tr>
            </thead>
            <tbody>
                <?php
                  $balanceQuantity = 0;
                ?>
                @foreach ($stockCard as $item => $val)
                  <tr>
                    <td>{{ $val['Date'] }}</td>
                    <td>{{ $val['Reference'] }}</td>
                    <td class="text-center">{{ ($val['ReceiptQty'] != 0) ? $val['ReceiptQty'] : "" }}</td>
                    <td class="text-center">{{ ($val['IssuanceQty'] != 0) ? $val['IssuanceQty'] : ""  }}</td>
                    <td class="text-center">{{ ($val['Office'] != 0) ? $val['Office'] : "" }}</td>
                    <td class="text-center">{{ $val['BalanceQty'] }}</td>
                  </tr>
                 @endforeach
            </tbody>
    </table>
  @php
    $filename = $fileName;
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=" . $filename);  
    header("Pragma: no-cache"); 
    header("Expires: 0");
  @endphp
</body>
</html>