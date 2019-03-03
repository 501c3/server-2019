###Team-Event Definition File
This file specifies the teams and the events that they may enter.  A sample file may be
found at [GitHub](../tests/Common/setup-08-event-team.yml)

See the short example at the end of this specification on how this specification relates
an event to a team.

```
<model specific team event list>

<model speicific team event list>::=
- <model name #1>: <model event block #1>
        .
        .
        .
- <model name #n>: <model event block #n>

<model name #i>::=
    ISTD Medal Exams|
    Georgia DanceSport Amateur-2019|
    Georgia DanceSport ProAm-2019
    ...
    
 <model event block #i>::=
    proficiency: <event-team proficiency matching>
    age: <event-team age matching>
    sex: <event-team sex matching>
    status: <event-team status matching>
    type: <event-team type matching>

 <event-team proficiency matching>::=
    <event proficiency>: [<team proficiency #1>,<team proficiency #2>,...]
    
 <event-team age matching>::=
    <event age>: [<team age #1>,<team age #2>,...]   
    
 <event-team sex matching>::=
    <event sex>: [<team sex #1>,<team sex #2>,...]
    
 <event-team status matching>::=
    <event status>: [<team status #1>,<team status #2>,...]
    
 <event-team type matching>::=
    <event type>: [<team type #1>,<team type #2>,...]
    
    
  <event proficiency>::=
     Social|Newcomer|Pre Bronze|Intermediate Bronze|Full Bronze|Open Bronze|
     Bronze|Pre Silver|Intermediate Silver|Full Silver|Open Silver|Silver|
     Pre Gold|Intermediate Gold|Full Gold|Open Gold|Gold|
     Novice|Pre Championship|Gold Star 1|Championship|Gold Star 2|
     Rising Star| Professional
  <team proficiency #i>::=
     Social|Newcomer|Pre Bronze|Intermediate Bronze|Full Bronze|Open Bronze|
     Bronze|Pre Silver|Intermediate Silver|Full Silver|Open Silver|Silver|
     Pre Gold|Intermediate Gold|Full Gold|Open Gold|Gold|
     Novice|Pre Championship|Gold Star 1|Championship|Gold Star 2|
     Rising Star| Professional
     
   <event age>::=
     Under 6|Under 8| Under 12|Junior 12-16|Adult 16-50|Senior 50|
     Preteen 1|Preteen 2|Junior 1|Junior 2|Youth|Adult|
     Senior 1|Senior 2|Senior 3|Senior 4|Senior 5|Senior|Baby|Juvenile|
     Youth-Senior|Youth-Senior 5|Adult-Senior 2|Senior 3-Senior 5|Adult-Youth|
     Senior-Youth|College-College|Adult-College|Baby-Preteen 2
   <team age #i>::=
       Y00-00|Y01-04|Y05-05|Y06-06|Y07-07|Y08-08|Y09-09|Y10-10|Y11-11|Y12-12|
       Y13-13|Y14-14|Y15-15|Y16-16|Y17-17|Y18-18|Y19-34|Y35-44|Y45-49|Y50-54|
       Y55-64|Y65-74|Y75-99      
         
   <event sex>::=
        Mixed Sex
   <team sex #i>::=
        Male,Female,Male-Female,Female-Female,Male-Male
        
   <event status>::=
        Student|Student-Student|Teacher-Student|Teacher-Teacher|
        
   <event type>::=Amateur|Amateur-Amateur|Professional-Amateur|Professional-Professional|
                           
```

Consider the following fragment from the team-event definition file.  The strings
on the left side of the colon are domains and descriptive elements for the **Event**.  
The strings on the right side of the colon are the descriptive elements for the **Team**. 

```yaml
  - proficiency:
      Pre Championship:
        - Gold Star 2
        - Pre Championship
      Championship:
        - Pre Championship
        - Gold Star 2
        - Championship
    age:
      Adult: [Y19-34,Y35-44]
      Senior 1: [Y35-44,Y45-49,Y50-54]
      Senior 2: [Y45-49,Y50-54,Y55-64]
      Senior 3: [Y55-64,Y65-74]
      Senior 4: [Y65-74,Y75-99]
      Senior 5: [Y75-99]
    sex:
      Same Sex: [Male-Male,Female-Female]
      Male-Female: [Male-Female]
    status:
      Student-Student: [Student-Student]
    type:
      Amateur-Amateur: [Amateur-Amateur]

```
Consider the following event:
Pre Championship, Adult, Same Sex, Student-Student, Amateur-Amateur

Teams with a proficiency of Gold Star 2 and Pre Championship and ages Y19-34, Y35-44
and sex Male-Male and Female-Female may enter this event.

[Prev](./doc06-event.md)