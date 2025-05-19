# UMD Terp Paragraphs: External Data Source

This is a custom variant of the external_data_source module, for use by UMD. It was broken off, as the original contrib module was somewhat unsupported.

Provides functionality for fetching Data from External APIs and displaying 
fetched data as a field options.

Any data source can be used as options of your Drupal Form Field: 
Select, Check-boxes or Auto complete can be introduced 
as an ExternalDataSource Plugin. You can checkout the Countries plugin 
to full understand how this module work.

So you can configure which plugin will gather data for your field using 
the field configuration form and Form Display options. 

# REQUIREMENTS
 This module doesn't require any other modules except the core Field module.

# CONFIGURATION

This module's designed for Drupal developers that's simply need to add an 
External Data Source Plugin that gather the required data from the external 
API. Then you can create a new External data field inside your field and 
select your plugin in the list to provide your field options.
