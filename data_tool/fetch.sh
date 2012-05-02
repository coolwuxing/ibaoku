#!/bin/bash

ids=`cat input`

for i in $ids
do
	echo "deal: ${i}"
	/Applications/MAMP/bin/php/php5.3.6/bin/php fetch_nick.php $i
done
