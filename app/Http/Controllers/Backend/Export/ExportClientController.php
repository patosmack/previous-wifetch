<?php

namespace App\Http\Controllers\Backend\Export;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Http\Request;

class ExportClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fileName = 'WIFETCH-Clients';
        $fileName = ucwords(Helper::slug($fileName)) . '.xlsx';
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($fileName);
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Clients');
        $headers = [
            'User ID',
            'Customer Name',
            'Customer Last Name',
            'Customer Email',
            'Customer Home Phone',
            'Customer Mobile Phone',
            'Created At',
        ];

        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(Color::rgb(0, 0, 0))
            ->setShouldWrapText(true)
            ->setBackgroundColor(Color::rgb(243, 228, 78))
            ->build();

        $rowFromValues = WriterEntityFactory::createRowFromArray($headers, $headerStyle);
        $writer->addRow($rowFromValues);

        $users  = User::all();

        $rowData = [];
        foreach ($users as $user){
            $flat = [
                $user->id,
                $user->name ?: '',
                $user->last_name ?: '',
                $user->email ?: '',
                $user->home_phone ?: '',
                $user->mobile_phone ?: '',
                $user->created_at ? $user->created_at->format('Y-m-d h:i') : '',
            ];
            $rowData[] = WriterEntityFactory::createRowFromArray($flat);
        }
        if(count($rowData) > 0){
            $writer->addRows($rowData);
        }
        $writer->close();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
