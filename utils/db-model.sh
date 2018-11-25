#!/bin/bash
./bin/console doctrine:mapping:convert --from-database --em=models --filter=Sequence --namespace='Entity\Models\' --force --no-debug annotation ./src
#for i in $(ls src/Entity/models/); do sed -i  's/Entity\\models/App\\Entity\\models/g' src/Entity/Models/${i}; done > /dev/null
