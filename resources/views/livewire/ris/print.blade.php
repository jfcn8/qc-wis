<!DOCTYPE html>
<html>
<head>
    <title>Requisition and Issue Slip - {{ session()->get('date_from') . ' - ' . session()->get('date_to') }}</title>
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
          display:table;
        }
        th {
          font-size: 11px !important;
          font-weight: 150;
          text-align: justify !important;
          width:100%;
          display:table;

    
        }
        th.bold {
          font-weight: 600;
        }
        td {
          font-size: 9px !important;
          width:100%;
          display:table;
        }
        table , th, td {
          border: 1px solid #000;
          padding-right: 15px;
          width:100%;
          display:table;
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
      
    </style>
</head>

@php
  $filename = "RIS_" . date('MdY', strtotime($ris->date_request)) . '_' . $ris->office->office.'.xls';
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=" . $filename);  
  header("Pragma: no-cache"); 
  header("Expires: 0");
@endphp

<body>

    
    <table class="table" border="1">
        <tr>
          <td colspan="5" class="text-center">
            <h2>Requisition and Issue Slip</h2>
            <h3>LGU - QUEZON CITY GOVERNMENT</h3>
          </td>
        </tr>
        <tr>
          <td width="33%" class="padding-left">Date Request : <h3>{{ date('F d, Y', strtotime($ris->date_request)) }}</h3></td>
          <td width="33%" class="padding-left">Office : <h3>{{ $ris->office->office }}</h3></td>
          <td colspan="3" width="33%" class="padding-left">Purpose : <h3>{{ $ris->purpose }}</h3></td>
        </tr>
      </table>


    <table class="table" cellspacing="0" role="grid" border="1" style="width: 100%;">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Item</th>
                <th class="text-center">Quantity</th>
                <th>Unit Value</th>
                <th>Cost Value</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalCostValue = 0; ?>
            @foreach ($itemLogs as $itemLog)
            <tr>
                <td>{{ $itemLog->reference }}</td>
                <td>{{ $itemLog->article . ' ' . $itemLog->description . ' - ' .  $itemLog->unit }}</td>
                <td class="text-center">{{ number_format($itemLog->quantity) }} </td>
                <td class="text-center">{{ number_format($itemLog->price, 2)}} </td>
                <td class="text-center">{{ number_format($itemLog->quantity * $itemLog->price, 2)}} </td>
            </tr>
            <?php
              $totalCostValue += ($itemLog->quantity * $itemLog->price);
            ?>
            @endforeach
            <tr>
              <td colspan="4">Total Cost Value</td>
              <td>{{ number_format($totalCostValue, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <script>
        print();
    </script>
</body>
</html>