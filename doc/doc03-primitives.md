##Primitives:Models:Domains,Values

###Competition Models File

The competition model file is a list of alpha numerics 
representing competition models.

Example of file contents
```
- ISTD Medal Exams
- Georgia DanceSport Amateur
- Georgia DanceSport ProAm
```

###Domain File

The domain file is a list of strings representing  
classification domains.  

As an example some of the domains used to classify
a person are as follows:
```
- style
- proficiency
- age
- sex
```

###Value File

The value file contains a listing of valid values within a domain.
It is a dictionary of acceptable terms and their abbreviation.  There
is an optional note field for comments. 
```
- domain01:
    - {name: Value1, abbr: Abbr1, note: Comment1}
    - {name: Value2, abbr: Abbr2, note: Comment2}
    ...
    - {name: ValueN, abbr: AbbrN, note: CommentN}
 ...
 - domain0N:  
    - {name: Value1, abbr: Abbr1, note: Comment1}
    - {name: Value2, abbr: Abbr2, note: Comment2}
    ...
    - {name: ValueN, abbr: AbbrN, note: CommentN} 
```

As an example, within dancesport there are International
and American styles of Ballroom.   Within International 
there are substyles of Standard and Latin.  These items
would be described in the dictionary as follows:

```
style:
    - {name: American, abbr: A, note: American Style}
    - {name: International, abbr: I, note: International Style}
substyle:
    - {name: Standard, abbr: Std }
    - {name: Latin, abbr: Ltn }
    
```

