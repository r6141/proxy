A project to calculate days you can bunk.

database is made using sqlite3, backend in php, and frontend probably in js or some js framework.

# goals

1. ~~add a section(wiki?) to explain how the data is stored in the db and how to access it~~
2. make 3 formats to view 
   - daily view = (shows all days from 1-30) 
     - has same tile as the days in the month
     - tile can be gree or red or blue, caluated if present, absent or not defined. (! not defined tiles are not taken into calculation for other views)
   - monthly view = (shows all months from 1-12)
     - has 12 tile, each of which shows overall stat for that month
     - tile can be green or red, calculated from percentage of days present.
   - year view = (shows all years)
     - has 1 tile, which shows overall stat 
     - tile can be green or red, calculated from percentage of days present.
  
