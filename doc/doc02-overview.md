#Introduction

The Georgia DanceSport Competition Registration Project is 
open source system for classifying competitors and determine 
their eligibility for various events in DanceSport. Georgia
DanceSport is currently analyzing the various classifications 
used by various competitions running under the auspices of 
NDCA and USA Dance as well as exam requirements of ISTD.

Georgia DanceSport currently sponsors a yearly 
competition in September.  The competition is used
for consumer testing of the registration process under
development.

##Definitions

A competition consists of the following entities:

* **Person** - meta data describing a person.
* **Team** - meta data describing a team.
* **Event** - meta data describing an event.

The these entities have the following relationships :

* A **Person** collection (1 person or 2 people) is aggregated into a **Team**.
* A **Team** is restricted to an **Event** collection it may enter.

All entities are described by a set of terms which fall within domains:
**type**, **status**, **proficiency**, **sex**, **age**,**style**,**substyle**,
**dance** and others which will be defined later in this documentation. 

####Person Meta-Data

A **Person** is described terms falling with the following domains:
* **type**-- has a value of "Professional" or "Amateur".
* **status**--has a value of "Teacher" or "Student".
* **years**--an actual age of an individual on Dec 31st of current year.
* **proficiency**--the proficiency of a **Person**.  Example "Bronze" or "Pre Silver".
* **designate** -- either "A" or "B"

In JSON notation a **Person** could be classified as
``` 
{"type": "Amateur","status": "Teacher", "years": 31, 
 "proficiency": "Pre Bronze", "designate": "A"}
```
 
####Team Class Meta-Data 
 
A **Team Class** is described also by a set of terms falling within the following domains:
 **type**,**status**,**sex**,**age**, **proficiency**.  Similarly a **Team** could 
 be described in JSON notation as
```
{"type": "Amateur-Amateur","status":"Student-Student",
"sex":"Male","age":"Y45-49","proficiency": "Silver"}
```
 
The "age": "Y45-49" value indicates the age qualification of a team for certain events. 
This particular value corresponds to the age of the oldest person comprising team
and is 45-49 years.  The younger member may be between 40-45 years but at the same time
meet the parameters for both members to enter for Senior 2 events in USA Dance
as an example.  (Note: For USA Dance events the nominal Senior 2 age qualifications is 
45-54.  Georgia DanceSport added extra team definition devisions for the purpose of 
computation efficiency.  These divisions are transparent to the user of the on-line 
registration.)  

Note that multiple person couplings (an instance of a team) could be assigned the same team class.  
  
####Event Meta-Data
An **Event** is described by a set of co-ordinates: **type**,**status**,**sex**,
**age**,**proficiency**.  The **proficiency** coordinate is a complex coordinate where the substyle and the dances
are defined. A typical event is defined by the following JSON notation:
```
{
 "age" : "Adult",
 "dances" : { "Smooth" : ["Waltz","Tango","Foxtrot"]},
 "proficiency" : "Novice",
 "sex" : "Same Sex",
 "status" : "Student-Student",
 "style" : "American",
 "tag" : "Qualifier",
 "type" : "Amateur-Amateur"
}
```

####Primitive Elements
A dictionary of terms and their abbreviations for describing a 
**Person**, **Team** or **Event** must first be declared prior 
to their use.  This is done through a collection of 4 yaml files.  

1. All possible domains are defined 
in a single file (See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-02-domains.yml)) 

2. All possible primitive values within a domain must subsequently be declared.
(See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-03-values.yml))

3. The competition models must be declared.
(See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-01-models.yml))

4. The relationship of these values declared 1 and 2 are related to
specific competition models. (See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-04-model-values.yml)) 

These primitives are subsequently used to define the meta data for a
possible **Person**, **Team**, and **Event**.  When defining events through a yaml file 
the primitives are checked for valid spelling and use.  For example, if you define
dances in a "Smoooth" substyle and then use one of the command line utilities to 
convert your yaml file to a database definition, an exception is thrown.  The system
recognizes only a "Smooth" substyle and may give an error message indicating the
proper spelling.

####Person,Team and Event Definitions

An additional 3 yaml files are used to define the relationship between people, teams and
events they may enter:

5. **Person** meta data is compiled for all possible people who may enter competition from
a yaml definition file. This file is used to generate JSON meta data for each specific person 
that may enter a competition.  
(See  [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-05-persons.yml) for an example file.)
6. **Team** meta data is compiled from a team definition file.  This file defines the 
relationships between a team and the people (via meta-data) who are part of the team. This 
file is used to generate JSON definitions for each team.  
(See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-06-teams.yml) for an example file.)
7. **Event** meta data is generated from a yaml event definition file. This file is used 
to generate JSON definitions for each event.
(See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-07-events.yml) for an example file.)
8. **Team** and the **Event** collection they may enter is determined by a yaml definition
file.
(See [GitHub](https://github.com/501c3/server-2019/blob/master/tests/Common/setup-08-event-team.yml) for an example file.) 

####Generating Person Meta-Data from Yaml Definition File.

A typical yaml definition file would have an entry as follows:
```
- type: Amateur 
  status: Student
  sex:
  - Male
  - Female
  age: 1-15
  proficiency:
  - Social
  - Newcomer
  designate:
  - A
  - B
```  
The above code block will generate 1 type x 1 status x 2 sexes x 15 ages x 2 designates
= 60 total individual JSON entries into the database.  

The first JSON meta-data definition generated from the above block would be 
```
{"type": "Amateur", "status": "Student", "years": 1, "proficiency": "Social","designate": "A"}
```

The last JSON meta-data definition generated from the above block would be
```
{"type": "Amateur", "status": "Student", "years": 15, "proficiency": "Newcomer", "designate": "B"}
```

####Generating Team Meta-Data from Yaml Definition File

A typical team definition file would have an entry as follows:
```
- type: Amateur-Amateur
  status: Student-Student
  sex:
    - Male-Female
    - Male-Male
    - Female-Female
  age:
    Senior-Youth: [40-75,1-12]
    Adult-Youth: [19-39,1-12]
  proficiency:
    Social:
    - Social
    - Pre Bronze
```
The above definition would generate 12 team classes.  The first
JSON record generated for a team class would look as follows:
```
{"type": "Amateur-Amateur", "status": "Student-Student", "sex": "Male-Female", 
"age": "Senior-Youth", "proficiency: "Social"}
``` 
For the above team class, 740 different combinations of persons can come together to
form that team.  A senior of 40 years can come together with a youngster of 1 year to 
form a team with the above classification.


The last JSON record generated for a team class would look as follows:

```
{"type": "Amateur-Amateur", "status": "Student-Student", "sex": "Female-Female", 
"age": "Adult-Youth", "proficiency: "Pre Bronze"}
```
Likewise, for the above team class, several hundred person combinations can
come together to form the above team class.

####Generating Event Meta-Data from a Yaml Definition File.

The following is an example of an entry in a yaml event definition file.

```
- proficiency:
    Pre Bronze:
    - tag: Qualifier
      style:
        American:
          disposition: multiple-events
          substyle:
            Rhythm: [[Rumba,Swing]]
            Smooth: [[Waltz,Tango]]
        International:
          disposition: multiple-events
          substyle:
            Latin: [[Cha Cha,Rumba]]
            Standard: [[Waltz,Quickstep]]
  age:
  - Preteen 1
  - Preteen 2
  sex: [Mixed Sex]
  type: Professional-Amateur
  status: [Teacher-Student]
```

The above entry defines 8 individual events.  A JSON definition is generated for an American
Rumba, Swing event for Preteen 1 and another Rumba, Swing event for Preteen 2. Other events
are generated for Standard and Smooth which also consist of 2 events for each nominal age category 
listed. 

Competition rules permit lower ages to dance up to a higher age in youth events. 
Lower proficiencies can dance up to a higher proficiency as well.  This is covered 
in the next section.

####Determining Team Eligibility for Events

An entry in an event team specification would look as follows:

```
ISTD Medal Exams-2019:
  - proficiency:
      Bronze:
      - Pre Bronze
      - Intermediate Bronze
      - Full Bronze
    age:
      Under 6: [Y01-04,Y05-05,Y06-06]
    sex:
      Mixed Sex: [Male-Female]
    status:
      Mixed Status: [Student-Student]
    type:
      Mixed Type: [Amateur-Amateur]
```
The above entry defines a collection of events and the teams eligible to enter them.
Any Bronze, Under 6 event can have teams of Pre Bronze, with Youth ages 1 to 4 years 
and Male-Female combinations.  Youth ages 5 years and 6 years are also permitted ages
to enter this event category.

[Prev](./doc01-introduction.md) [Next](./doc03-primitives.md)