###Person Entity Generation File

The person entity generation file is yaml list.  Each
collection is mutually exclusive and generates all 
possible meta for all competition models.



```
- <person meta data collection #1>
        .
        .
        .
- <person meta data bollection #n>  

```


```
<person meta data collection>::=

type: <type selection>
status: <status selection>
years: <year range>
sex: <sex selection>
proficiency: <proficiency selection>
designate: <designate selection>

```
The years, proficiency and designate fields are lists.  Each value in each of these
lists are combined to produce meta data for a multiple persons.  Consider the following
example:

```
type: Teacher
status: Amateur
years: 20-25
sex: [Male,Female]
proficiency: [Pre Championship, Championship]
designate: [A,B]

```
The above code fragment will produce 5 years x 2 proficiencies x 2 sexes x 2 designates = 40 possible
value combinations for Amateur-Teacher meta-data.  The Backus-Nauer
spec below describe the permitted values in each field.

```
<type selection>::= Professional|Amateur
<status selection>::=Teacher|Student
<sex selection>::=[<Sex Subset>]
<years range>::= \d*-\d*
<proficiency selection>::= <Proficiency Subset>
<designate selection>::= A|B
```

We are using regular expression notation indicate the <years range>.  \d*-\d* indicates
a year range such as 1-15 for 1 year to 15 years.

```
<Sex Subset> consists of one or more of the following:
    Male|Female

<Proficiency Subset> consists of one or more of the following:
  Social|Newcomer|Pre Bronze|Intermediate Bronze|Full Bronze|
  Open Bronze|Bronze|Pre Silver|Intermediate Silver|Full Silver|Open Silver
  Silver|Pre Gold|Intermediate Gold|Full Gold|Open Gold|Gold|Novice
  Pre Championship|Gold Star 1|Championship|Gold Star 2||Rising Star
  Professional

```
The meta data is generated from all combinations of domain values.  
Example:

```
One particular combination would be 
type: Amateur
status: Student
proficiency: Bronze
sex: Female
years: 21
designate: A

```

[Pref](./doc03-primitives.md) [Next](./doc05-team.md)