# modx-simple-events-calendar
Simple Steps to create an Events Calendar in MODX with dedicated Event Pages

1. Create a Template called 'Event' so we can assign Template Variables to it.
2. Create a Template Variable 'start_date' that has the 'Date' Input Type
3. Create a Template Variable 'end_date' that has the 'Date' Input Type
4. Create a Resource for the Calendar. It will also be the parent for the 'Event' Children resources
5. Call the snippet `[[!Calendar]]` in that resource.
6. Add 'Event' Children 
