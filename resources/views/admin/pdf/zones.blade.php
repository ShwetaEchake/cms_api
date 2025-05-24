<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        /* Set A3 page size */
        @page {
            size: A3 landscape;
            margin: 20mm; /* Add some margin for better spacing */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
        }

        .card {
            /* width: 100%;
            padding: 20px; */
        }

        .card-header h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        .card-body {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid black;
            padding: 8px 12px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table td {
            font-size: 10pt;
        }

        table td, table th {
            text-align: left;
            vertical-align: middle;
        }

        thead, tfoot {
        display: table-row-group;
        }
    </style>
</head>


<body>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        {{-- <div class="">
                            <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                            <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                        </div> --}}
                        {{-- <button id="downloadPdf" class="btn btn-flat btn-danger">PDF</button> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Symbol</th>
                                <th>Open</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Prev Close</th>
                                <th>ltp</th>
                                <th>Indicative Close</th>
                                <th>Chng</th>
                                <th>Volume</th>
                                <th>Value</th>
                                <th>Sale</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $value->symbol }} </td>
                                    <td> {{ $value->open }} </td>
                                    <td> {{ $value->high }} </td>
                                    <td> {{ $value->low }} </td>
                                    <td> {{ $value->prev_close }} </td>
                                    <td> {{ $value->ltp }} </td>
                                    <td> {{ $value->indicative_close }} </td>
                                    <td> {{ $value->chng }} </td>
                                    <td> {{ $value->volume }} </td>
                                    <td> {{ $value->value }} </td>
                                    <td><strong> {{ $value->sale }} </strong></td>
                                    {{-- <td>
                                        <button class="edit-element btn btn-primary px-2 py-1" title="Edit zone" data-id="{{ $zone->id }}"><i data-feather="edit"></i></button>
                                        <button class="btn btn-dark rem-element px-2 py-1" title="Delete zone" data-id="{{ $zone->id }}"><i data-feather="trash-2"></i> </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

