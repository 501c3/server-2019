###Team Entity Generation File

The team entity generation file is a yaml file with a list of collections.  
Each collection generates meta data which is mutually exclusive:

A sample file implementing this specification may be found at 
[GitHub](../tests/Common/setup-06-teams.yml)

```
- <team meta data collection #1>
        .
        .
        .
- <team meta data collection #2>

``` 

```
  <team meta data collection>::=
  
  type: <type meta data>
  status: <status meta data>
  sex: [<sex meta data list>]
  age: [<age meta data list>]
  proficiency: [<proficiency meta data list>]
  
```

```
  <type meta data>::= Amateur|Amateur-Amateur|Professional-Amateur|Professional-Professional
  
  <status meta data>::=Teacher|Student|Teacher-Student
  
  <sex meta data list>::=<sex meta data>*
  
  <age meta data list>::=<age meta data>+
  
  <proficiency meta data list>::=<proficiency meta data>*
```

```
<sex meta data>::=Male|Female|Male-Female|Male-Male|Female-Female
<age meta data>::=<team age>: <person age range>
<proficiency meta data>::=<team proficiency>:  [<subordinate partner proficiencies>]
```

```
<team age>::=
    Y01-04|Y05-05|Y06-06|Y07-07|Y08-08|Y09-09|Y10-10|Y11-11|
    Y12-12|Y13-13|Y14-14|Y15-15|Y16-16|Y17-17|Y18-18|Y00-00
    
<person age range>::=[\d+-\d+|\d+-\d+,\d+-\d+]

Examples:
<person age range>::=[1-15]
<person age range>::=[40-65,1-15]    
```

In the first example meta data is generated for all years 1,2,...15.  In the second
example above meta data is generated for all possible couples ranging in age from 40-65 
years for the first partner and 1-15 years for the second partner.  There is a total
of 15x15=225 possible age combinations for the partnership.  In Teacher-Student coupling 
only student age is considered in deciding the age classification of the couple.  

```
<team proficiency>::=
    Newcomer|Pre Bronze|Intermediate Bronze|Full Bronze|Open Bronze|Bronze
    Pre Silver|Intermediate Silver|Full Silver|Open Silver|Silver|
    Pre Gold|Intermediate Gold|Full Gold|Open Gold|Gold|
    Gold Star 1|Novice|Gold Star 2|Pre Championship|Championship|
    Rising Star|Professional
    
<subordinate partner proficiencies>::=subset of zero or more of the following:
    Newcomer|Pre Bronze|Intermediate Bronze|Full Bronze|Open Bronze|Bronze
    Pre Silver|Intermediate Silver|Full Silver|Open Silver|Silver|
    Pre Gold|Intermediate Gold|Full Gold|Open Gold|Gold|
    Gold Star 1|Novice|Gold Star 2|Pre Championship|Championship|
    Rising Star|Professional

```

The subordinate partner proficiency is different from the team proficiency. For 
Amateur-Amateur/Student-Student events the subordinate partner proficiency is lower
than the team proficiency.  For Teacher-Student proficiencies, the partner proficiency
is always higher then the team proficiency and the team proficiency corresponds to
the proficiency of the student.

[Prev](./doc04-person.md) [Next](./doc06-event.md)
