###Event Entity Generation File

This file contains the event specifications for specific competition models.  
At the present time 3 competition modesl are specified:
* ISTD Model Exams
* Georgia DanceSport Amateur-2019
* Georgia DanceSport ProAm-2019

Future models will be added over time.  

A sample file implementing this specification may be found at 
[GitHub](../tests/Common/setup-07-events.yml)


```
<model specific event list>


<model specific event list>::=
    <model name #1>: <event definition block #1>
            .
            .
            .
    <model name #n>: <event definition block #n>
    
<model name #i>::= ISTD Medal Exams-2019|
                   Georgia DanceSport Amateur-2019|
                   Georgia DanceSport ProAm-2019|
                ...
                
<event definition block #i>::= 
    - <event definition block #1>
            .
            .
            .
    - <event definition block #n>
        
<event definition block #i>::=
    proficiency: [<proficiency event list>] 
    age: [<age list>]
    sex: [<sex list>]
    type: [<type list>]
    status: [<status list>]
```

```
<proficiency event list>::=
    <proficiency #1>: [<tag style events list #1>]
            .
            .
            .
    <proficiency #n>: [<tag style events list#n>]
    
<proficiency #i>::=
      Social|Newcomer|Pre Bronze|Intermediate Bronze|Full Bronze|Open Bronze|Bronze
      Pre Silver|Intermediate Silver|Full Silver|Open Silver|Silver
      Pre Gold|Intermediate Gold|Full Gold|Open Gold|Gold|Novice
      Pre Championship|Gold Star 1|Championship|Gold Star 2|Rising Star
      Professional    
      
<tag style events #i>::=
    tag: <tag item>
    style: <style event item>
    
<tag item>::=Qualifier|Scholarship

<style event item>::=
   <style name #1>: <disposition substyle #1>
   <style name #2>: <disposition substyle #n>              
        ...

<style name #i>::=American|International|Fun Events
```

```
<disposition substyle #i>::=
    disposition: <disposition>
    substyle:
        <substyle #1>: [<dance collection #1>,...,<dance collection #n]
        <substyle #2>: [<dance collection #2>,...,<dance collection #m]
            ...
<disposition>::= single-event|multiple-events

<substyle #i>::= Rhythm|Smooth|Standard|Latin|Novelty

<dance collection #i>::= [dance #1,...,dance #n]
    
<dance #i>::=
  Cha Cha|Samba|Rumba|Paso Doble|Jive|Swing|Bolero|Mambo|Salsa|West Coast Swing|
  Merengue|Night Club|Hustle|Chicken Dance|Waltz|Tango|Viennese Waltz|Foxtrot|
  Quickstep|Argentine Tango|Peabody|Valtz|Melonga|2-Step|Nightclub|Polka|
  Triple Two|Performance
```
A disposition of 'single-event' indicates that all dance collections specified in the 
style field will be aggregated into 1 single event for each proficiency, age, sex etc.  
A disposition of multiple-events indicates that each dance collection will constitute 
its own event for each proficiency, sex, age etc. 

