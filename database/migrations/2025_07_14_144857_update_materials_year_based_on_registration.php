<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            UPDATE materials
            SET year = CASE
                WHEN registration BETWEEN 1 AND 86999 THEN 1971
                WHEN registration BETWEEN 87000 AND 90691 THEN 1972
                WHEN registration BETWEEN 90692 AND 100521 THEN 1973
                WHEN registration BETWEEN 100522 AND 104899 THEN 1974
                WHEN registration BETWEEN 104900 AND 113303 THEN 1975
                WHEN registration BETWEEN 113304 AND 118103 THEN 1976
                WHEN registration BETWEEN 118104 AND 125458 THEN 1977
                WHEN registration BETWEEN 125459 AND 129602 THEN 1978
                WHEN registration BETWEEN 129603 AND 133728 THEN 1979
                WHEN registration BETWEEN 133729 AND 139237 THEN 1980
                WHEN registration BETWEEN 139238 AND 146094 THEN 1981
                WHEN registration BETWEEN 146095 AND 148958 THEN 1982
                WHEN registration BETWEEN 148959 AND 152423 THEN 1983
                WHEN registration BETWEEN 152424 AND 154089 THEN 1984
                WHEN registration BETWEEN 154090 AND 170171 THEN 1985
                WHEN registration BETWEEN 170172 AND 174111 THEN 1986
                WHEN registration BETWEEN 174112 AND 178146 THEN 1987
                WHEN registration BETWEEN 178147 AND 180922 THEN 1988
                WHEN registration BETWEEN 180923 AND 182064 THEN 1989
                WHEN registration BETWEEN 182065 AND 183885 THEN 1990
                WHEN registration BETWEEN 183886 AND 187621 THEN 1991
                WHEN registration BETWEEN 187622 AND 189633 THEN 1992
                WHEN registration BETWEEN 189634 AND 191513 THEN 1993
                WHEN registration BETWEEN 191514 AND 193766 THEN 1994
                WHEN registration BETWEEN 193767 AND 198600 THEN 1995
                WHEN registration BETWEEN 198601 AND 202299 THEN 1996
                WHEN registration BETWEEN 202300 AND 205603 THEN 1997
                WHEN registration BETWEEN 205604 AND 209394 THEN 1998
                WHEN registration BETWEEN 209395 AND 217948 THEN 1999
                WHEN registration BETWEEN 217949 AND 222545 THEN 2000
                WHEN registration BETWEEN 222546 AND 224242 THEN 2001
                WHEN registration BETWEEN 224243 AND 226162 THEN 2002
                WHEN registration BETWEEN 226163 AND 227972 THEN 2003
                WHEN registration BETWEEN 227973 AND 229000 THEN 2004
                WHEN registration BETWEEN 229001 AND 230259 THEN 2005
                WHEN registration BETWEEN 230260 AND 232049 THEN 2006
                WHEN registration BETWEEN 232050 AND 233802 THEN 2007
                WHEN registration BETWEEN 233803 AND 242807 THEN 2008
                WHEN registration BETWEEN 242808 AND 249717 THEN 2009
                WHEN registration BETWEEN 249718 AND 256167 THEN 2010
                WHEN registration BETWEEN 256168 AND 270804 THEN 2011
                WHEN registration BETWEEN 270805 AND 281382 THEN 2012
                WHEN registration BETWEEN 281383 AND 289179 THEN 2013
                WHEN registration BETWEEN 289180 AND 294873 THEN 2014
                WHEN registration BETWEEN 294874 AND 298168 THEN 2015
                WHEN registration BETWEEN 298169 AND 301362 THEN 2016
                WHEN registration BETWEEN 301363 AND 304457 THEN 2017
                WHEN registration BETWEEN 304458 AND 307772 THEN 2018
                WHEN registration BETWEEN 307773 AND 312312 THEN 2019
                WHEN registration BETWEEN 312313 AND 314925 THEN 2020
                WHEN registration BETWEEN 314926 AND 315705 THEN 2021
                WHEN registration BETWEEN 315707 AND 317629 THEN 2022
                WHEN registration BETWEEN 317630 AND 319137 THEN 2023
                WHEN registration >= 319138 THEN 2024
            END
            WHERE registration IS NOT NULL
        ");
    }

    public function down()
    {
        // Opcional: Se quiser reverter, pode colocar aqui o que fazer.
    }
};
