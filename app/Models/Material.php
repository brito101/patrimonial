<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected array $dates = ['deleted_at'];

    protected $fillable = [
        'registration',
        'secondary_code',
        'serial_number',
        'description',
        'observations',
        'value',
        'group_id',
        'department_id',
        'status',
        //'year',
        'user_id',
        'write_off_date_at',
    ];

    /** Accessors */
    public function getValueAttribute($value)
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    public function setRegistrationAttribute($value)
    {
        $this->attributes['registration'] = $value;
        $this->attributes['year'] = match (true) {
            $value >= 1 && $value <= 86999 => 1971,
            $value >= 87000 && $value <= 90691 => 1972,
            $value >= 90692 && $value <= 100521 => 1973,
            $value >= 100522 && $value <= 104899 => 1974,
            $value >= 104900 && $value <= 113303 => 1975,
            $value >= 113304 && $value <= 118103 => 1976,
            $value >= 118104 && $value <= 125458 => 1977,
            $value >= 125459 && $value <= 129602 => 1978,
            $value >= 129603 && $value <= 133728 => 1979,
            $value >= 133729 && $value <= 139237 => 1980,
            $value >= 139238 && $value <= 146094 => 1981,
            $value >= 146095 && $value <= 148958 => 1982,
            $value >= 148959 && $value <= 152423 => 1983,
            $value >= 152424 && $value <= 154089 => 1984,
            $value >= 154090 && $value <= 170171 => 1985,
            $value >= 170172 && $value <= 174111 => 1986,
            $value >= 174112 && $value <= 178146 => 1987,
            $value >= 178147 && $value <= 180922 => 1988,
            $value >= 180923 && $value <= 182064 => 1989,
            $value >= 182065 && $value <= 183885 => 1990,
            $value >= 183886 && $value <= 187621 => 1991,
            $value >= 187622 && $value <= 189633 => 1992,
            $value >= 189634 && $value <= 191513 => 1993,
            $value >= 191514 && $value <= 193766 => 1994,
            $value >= 193767 && $value <= 198600 => 1995,
            $value >= 198601 && $value <= 202299 => 1996,
            $value >= 202300 && $value <= 205603 => 1997,
            $value >= 205604 && $value <= 209394 => 1998,
            $value >= 209395 && $value <= 217948 => 1999,
            $value >= 217949 && $value <= 222545 => 2000,
            $value >= 222546 && $value <= 224242 => 2001,
            $value >= 224243 && $value <= 226162 => 2002,
            $value >= 226163 && $value <= 227972 => 2003,
            $value >= 227973 && $value <= 229000 => 2004,
            $value >= 229001 && $value <= 230259 => 2005,
            $value >= 230260 && $value <= 232049 => 2006,
            $value >= 232050 && $value <= 233802 => 2007,
            $value >= 233803 && $value <= 242807 => 2008,
            $value >= 242808 && $value <= 249717 => 2009,
            $value >= 249718 && $value <= 256167 => 2010,
            $value >= 256168 && $value <= 270804 => 2011,
            $value >= 270805 && $value <= 281382 => 2012,
            $value >= 281383 && $value <= 289179 => 2013,
            $value >= 289180 && $value <= 294873 => 2014,
            $value >= 294874 && $value <= 298168 => 2015,
            $value >= 298169 && $value <= 301362 => 2016,
            $value >= 301363 && $value <= 304457 => 2017,
            $value >= 304458 && $value <= 307772 => 2018,
            $value >= 307773 && $value <= 312312 => 2019,
            $value >= 312313 && $value <= 314925 => 2020,
            $value >= 314926 && $value <= 315705 => 2021,
            $value >= 315707 && $value <= 317629 => 2022,
            $value >= 317630 && $value <= 319137 => 2023,
            $value >= 319138 => 2024,
            default => null,
        };
    }
}
