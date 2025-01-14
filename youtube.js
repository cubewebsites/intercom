// First, we identify our plugin with identifyPlugin(<plugin name>)
identifyPlugin("youtube");

// This allows intercom to list our plugin when the user executes 'help'
// Then, we add our 'hook' parser to the parser list, which lets intercom run
// user input by our function when entered. This way, we can parse user input
// when it is meant for our module.
addParser(youtube_hook);

// Here we declare a simple helptext variable which we will use later. It is 
// useful to have a helptext for a program which requires argumenrs or flags.
helpText = "<strong>Welcome to the <i>intercom youtube app</i>.</strong><br />" + 
"To get started, type 'youtube run'";


/**
 * Useful variables
 */
lightbox_enabled	=	true;
lastresults			=	[];
lastparams			=	[];
currentpage			=	0;

// This is our hook function. This will be run whenever a user enters input.
// This function should look for initiating calls and act accordingly.
// Here, we have our hook print the helptext when there are no arguments,
// or enter the parser for the demo if the argument 'run' is included.
function youtube_hook(input) {

	// Check the command. The command is what comes before the first space in a
	// input string. We check to see if it equals "youtube".
	if (checkCommand(input, "youtube")) {
  
		// quitParse() ends intercom's standard parse.
		quitParse();
    
		// Save the arguments and flags to variables
		youtube_arguments = extractArguments(input);
		youtube_flags = extractFlags(input);
    
		// No arguments -- display help
		if (youtube_arguments.length == 1) {
			output(helpText);
		}
    
		// Check for other args, if it equals "run" it will enter the youtube_parser
		// input stream.
		else if (youtube_arguments[1] == "run") {
			setInputStream(youtube_parser);
			output("The YouTube parser is now active! To quit, use the <i>forcequit</i> command.");
			outputHelpCommands();
		}
	}
}

/**
 * All the available commands for interaction with the YouTube video library
 */
function outputHelpCommands() {
  output("----------------------");
  output("Youtube basic commands");
  output("----------------------");  
  output("<b>toprated</b> - top rated videos on YouTube");
  output("<b>mostviewed</b> - most viewed videos on YouTube");
  output("<b>recentlyfeatured</b> - recently featured videos on YouTube");
  output("<b>user</b> - find videos by a specified user. Use <i>-u[sername]=username</i> to specify user.");
  output("<b>search</b> - search by keyword. Use <i>-q[uery]=whatever</i> to specify search term.");
  output("<b>lightbox</b> - determine whether videos should open in a lightbox or a new window.  The second parameter is a boolean (1/0)");
  output("<b>help</b> - display help message");
  output("<b>clear</b> - clears the content of the screen");
  output("----------------------");
}
// This is our custom input stream. It has to take a single paramater which
// intercom will use to send all user input directly to the input stream
// once set.
function youtube_parser(input) {
	output(input);
	youtube_arguments = extractArguments(input);
	youtube_flags = extractFlags(input);
  
	//get top rated videos
	if(youtube_arguments[0]=='toprated') {		
		output("Fetching top rated videos...");
		youtube_video_search('toprated',{});
	}
	
	//get most viewed
	else if(youtube_arguments[0]=='mostviewed') {		
		output("Fetching most viewed videos...");
		youtube_video_search('mostviewed',{});
	}
	
	//get recently featured
	else if(youtube_arguments[0]=='recentlyfeatured') {		
		output("Fetching recently featured videos...");
		youtube_video_search('recentlyfeatured',{});
	}
	
	//search videos by users
	else if(youtube_arguments[0]=='user') {
		if(!hasFlag(youtube_flags,"username") && !hasFlag(youtube_flags,"u")) {
			output("Please provide a username using the 'username' flag e.g. 'youtube user -u=cubewebsites'")
		}
		else {
			username	=	flagValue(youtube_flags,"u");
			if(!username.length)
				username	=	flagValue(youtube_flags,"username");
			output("Fetching videos for user: "+username+"...");
			youtube_video_search('user',{'username':username});
		}
	}
	
	//search YouTube
	else if(youtube_arguments[0]=='search') {
		if(!hasFlag(youtube_flags,"query") && !hasFlag(youtube_flags,"q")) {
			output("Please provide a search query using the 'query' flag e.g. 'youtube search -q=cat'")
		}
		else {
			query		=	flagValue(youtube_flags,"q");			
			if(!query.length)
				query	=	flagValue(youtube_flags,"query");
			output("Searching for: "+query+"...");
			youtube_video_search('search',{'query':query});
		}
	}  	
	//help command
	else if(youtube_arguments[0]=='help') {
		outputHelpCommands();
	}
	//clear command
	else if(youtube_arguments[0]=='clear') {
		clearScreen();
	}
	
	//lightbox toggle
	else if(youtube_arguments[0]=='lightbox') {		
		lightbox_enabled	=	youtube_arguments[1]==0?0:1;
		if(lightbox_enabled==1)	output("Lightbox enabled");
		else					output("Lightbox disabled");
	}
	
}

/**
 * Display a list of available options when the user is manipulating a set of video results
 */
function outputVideoResultCommands() {
	output("---------------------");
	output("Video result commands");
	output("---------------------");
	output("<b>help</b> - display help message");
	output("<b>q</b> - exit video results, start a new search");
	output("<b>info</b> - shows information about a selected video. e.g. <i>info 1</i>");
	output("<b>play</b> - plays a selected video. e.g. <i>play 1</i>");
	output("<b>next</b> - displays the next set of results");
	output("<b>prev</b> - displays the previous set of results");
	output("<b>page</b> - skips to a selected results page. E.g. <i>page 4</i>");
	output("---------------------");
}

/**
 * Input stream for handling a set of video results
 */
function youtube_video_result_parser(input) {
	outputWithCarrot(input);	
	youtube_arguments	= extractArguments(input);
	youtube_flags		= extractFlags(input);
	//exit
	if(youtube_arguments[0]=='q') {
		output("Exit successful");
		//outputHelpCommands();
		setInputStream(youtube_parser);
	}
	//help
	else if(youtube_arguments[0]=='help')
		outputVideoResultCommands()	
	// video information
	else if(youtube_arguments[0]=='info') {
		//make sure a video is selected
		if(!youtube_arguments[1]) {
			output("Select a video to view information for. E.g. <i>info 1</i>")
		}
		else {
			videoindex	=	youtube_arguments[1];
			if(videoindex > lastresults.length)
				output("Invalid video selected, try again");
			else {
				youtube_video_info(lastresults[videoindex].videoid);
			}
		}
	}
	//view video
	else if(youtube_arguments[0]=='play') {
		if(!youtube_arguments[1])
			output("Select a video to view. E.g. <i>play 1</i>");
		else {
			if(youtube_arguments[1] > lastresults.length)
				output("Invalid video selected, try again");
			else				
				$.prettyPhoto.open(lastresults[youtube_arguments[1]].url);			
		}
	}	
	//next set of results
	else if(youtube_arguments[0]=='next') {		
		repeatsearch(currentpage+1);
	}
	
	//previous set of results
	else if(youtube_arguments[0]=='prev') {		
		if(currentpage-1<1)
			output("Invalid page number specified");
		else
			repeatsearch(currentpage-1);
	}
	else if(youtube_arguments[0]=='page') {
		if(!youtube_arguments[1])
			output("Select a page to go to. E.g. <i>page 1</i>");
		else if(youtube_arguments[1]<1)
			output("Invalid page number specified");
		else
			repeatsearch(youtube_arguments[1]);
	}
	
}

/**
 * Fetches videos based on the selected search mode
 */
function youtube_video_search(search_mode,params) {
	var myvars	=	{mode:search_mode};
	$.each(params,function(key,value) {
		myvars[key]	=	value;
	});
	if(!myvars['page']) myvars['page']	=	1;	
	$.ajax({		
		data:		myvars,
		dataType:	'json',
		url:		basepath + 'app/videos.php',
		cache:		false,
		success:	function(data) {		
			//display results
			if(data.length > 0) {
				for(var i=0;i<data.length;i++) {
					output('<a videoid="'+data[i].videoid+'" rel="prettyPhoto" href="'+data[i].url+'" target="_blank">['+i+'] '+data[i].title+'</a>');
				}
				//setup lightbox if enabled
				if(lightbox_enabled) 
					$("a[rel^='prettyPhoto']").prettyPhoto({deeplinking: false});
				//setup the result parser to take in future inputs
				lastresults	=	data;
				lastparams	=	myvars;
				currentpage	=	myvars['page'];
				setInputStream(youtube_video_result_parser);
				output("Page: "+currentpage);
				outputVideoResultCommands();
			}
			//display error if no videos found
			else {				
				output('<span class="error">No results found</span>');
			}
		},
		error:		function(data) {
			output("Unable to retrieve results, try again");
		}
	});
}

function repeatsearch(page) {
	var params		=	lastparams;
	params['page']	=	page;
	youtube_video_search(params['mode'],params);
}

/**
 * Fetches HTML formatted information about a selected video
 */
function youtube_video_info(video) {
	$.ajax({	
		data:		{
			'video':	video
		},
		dataType:	'html',
		url:		basepath + 'app/videoinfo.php',
		cache:		false,
		success:	function(data) {
			output(data);
		},
		error:		function(error) {
			output("Unable to fetch video information");
		}
	});
}