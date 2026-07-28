<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceMaster extends Model
{
    protected $table = 'Servicemaster';
    protected $primaryKey = 'Serviceid';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'Serviceid',
        'ServiceCode',
        'ServiceName',
        'Rate',
        'DiscountPercentage',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'ModifiedBy',
        'Type',
        'ConversationRatio',
        'Status',
        'kidType',
        'ReportFlag',
        'BreakupReport',
        'ServiceChargeEditable',
        'TreatFollow',
        'AFT',
        'ServiceQty',
        'MaxLimit',
        'ConsolidateFlag',
        'SACNumber',
        'MaxDiscount',
        'GSTPerValueS',
        'surgery',
        'AFTFlag',
        'PrimaryFlag',
        'CServiceType',
        'QtyConversion',
        'Incentiveamt',
        'OldCode',
        'SubProduct',
        'SubUOM',
        'SubQty',
        'LoanDiscount',
        'Category',
        'BAPhoto',
    ];
}
