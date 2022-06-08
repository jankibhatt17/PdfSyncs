
<!DOCTYPE html>

<html dir="ltr" mozdisallowselectionprint>
  <head>
{{-- custom pdf path --}}
<input hidden id="pdf" value="{{ $_GET['pdfname'] }}">
<script>
   window.pdfname=document.getElementById("pdf").value;
</script>
{{-- custom pdf path end --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="google" content="notranslate">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sync Pdf</title>


    <link rel="stylesheet" href="../../../pdfjs-2.1.266-dist/web/viewer.css">


<!-- This snippet is used in production (included from viewer.html) -->
<link rel="resource" type="application/l10n" href="locale/locale.properties">
<script src="../../../pdfjs-2.1.266-dist/build/pdf.js"></script>


    <script src="../../../pdfjs-2.1.266-dist/web/viewer.js"></script>

    <!-- $FB: additional files included -->
    <script src="../../jquery-3.4.1.min.js"></script>
    <script src="../../turn.min.js"></script>
    <link rel="stylesheet" href="../../../pdf-turn/pdf-turn.css">
    <script src="../../../pdf-turn/pdf-turn.js"></script>

    {{-- Drop Down CSS Start --}}

    <style>
      .dropbtn {
        background-color: #0f2533;
        color: white;
        padding: 6px;
        font-size: 16px;
        border: white;
        cursor: pointer;
      }
      -scrolling {
        height: 5000px; /* Make this site really long */
        width: 100%; /* Make this site really wide */
        overflow: hidden; /* Hide scrollbars */
                      }
      .colorchange{
        background-color: #437ac8;
                }
      .start-scrolling {
        height: 100%; /* Make this site really long */
        width: 100%; /* Make this site really wide */
        overflow: show; /* Hide scrollbars */
      }
      .dropbtn:hover, .dropbtn:focus {
        background-color: #2980B9;
      }
      
      .dropdown {
        position: relative;
        display: inline-block;
      }
      
      .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
      }
      
      .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
      }
      
      .dropdown a:hover {background-color: #ddd;}
      
      .show {display: block;}
      @media screen and (max-width: 500px) {
        .top-btn{
          padding: 7px 2px !important;
          font-size: 12px !important;
          margin: 2px !important;
        }
        #viewFind{
          display: none;
        }
        #pageNumber{
          display: none;
        }
        #numPages{
          display:none;
        }
        .splitToolbarButton:first-child{
          margin-left: 0px !important;
        }
      }
      </style>

                                             {{-- DropDown CSS End --}}

  </head>

  <body tabindex="1" class="loadingInProgress">
    <div id="outerContainer">
      <div id="sidebarContainer">
        <div id="toolbarSidebar">
          <div class="splitToolbarButton toggled">
            <button id="viewThumbnail" class="toolbarButton toggled" title="Show Thumbnails" tabindex="2" data-l10n-id="thumbs">
               <span data-l10n-id="thumbs_label">Thumbnails</span>
            </button>
            <button id="viewOutline" class="toolbarButton" title="Show Document Outline (double-click to expand/collapse all items)" tabindex="3" data-l10n-id="document_outline">
               <span data-l10n-id="document_outline_label">Document Outline</span>
            </button>
            <button id="viewAttachments" class="toolbarButton" title="Show Attachments" tabindex="4" data-l10n-id="attachments">
               <span data-l10n-id="attachments_label">Attachments</span>
            </button>
          </div>
        </div>
        <div id="sidebarContent">
          <div id="thumbnailView">
          </div>
          <div id="outlineView" class="hidden">
          </div>
          <div id="attachmentsView" class="hidden">
          </div>
        </div>
        <div id="sidebarResizer" class="hidden"></div>
      </div>  <!-- sidebarContainer -->

      <div id="mainContainer">
        <div class="findbar hidden doorHanger" id="findbar">
          <div id="findbarInputContainer">
            <input id="findInput" class="toolbarField" title="Find" placeholder="Find in document…" tabindex="91" data-l10n-id="find_input">
            <div class="splitToolbarButton">
              <button id="findPrevious" class="toolbarButton findPrevious" title="Find the previous occurrence of the phrase" tabindex="92" data-l10n-id="find_previous">
                <span data-l10n-id="find_previous_label">Previous</span>
              </button>
              <div class="splitToolbarButtonSeparator"></div>
              <button id="findNext" class="toolbarButton findNext" title="Find the next occurrence of the phrase" tabindex="93" data-l10n-id="find_next">
                <span data-l10n-id="find_next_label">Next</span>
              </button>
            </div>
          </div>

          <div id="findbarOptionsOneContainer">
            <input type="checkbox" id="findHighlightAll" class="toolbarField" tabindex="94">
            <label for="findHighlightAll" class="toolbarLabel" data-l10n-id="find_highlight">Highlight all</label>
            <input type="checkbox" id="findMatchCase" class="toolbarField" tabindex="95">
            <label for="findMatchCase" class="toolbarLabel" data-l10n-id="find_match_case_label">Match case</label>
          </div>
          <div id="findbarOptionsTwoContainer">
            <input type="checkbox" id="findEntireWord" class="toolbarField" tabindex="96">
            <label for="findEntireWord" class="toolbarLabel" data-l10n-id="find_entire_word_label">Whole words</label>
            <span id="findResultsCount" class="toolbarLabel hidden"></span>
          </div>

          <div id="findbarMessageContainer">
            <span id="findMsg" class="toolbarLabel"></span>
          </div>
        </div>  <!-- findbar -->

        <div id="secondaryToolbar" class="secondaryToolbar hidden doorHangerRight">
          <div id="secondaryToolbarButtonContainer">
            <button id="secondaryPresentationMode" class="secondaryToolbarButton presentationMode visibleLargeView" title="Switch to Presentation Mode" tabindex="51" data-l10n-id="presentation_mode">
              <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
            </button>

            <button Style="display:none;" id="secondaryOpenFile" class="secondaryToolbarButton openFile visibleLargeView" title="Open File" tabindex="52" data-l10n-id="open_file">
              <span data-l10n-id="open_file_label">Open</span>
            </button>

            <button Style="display:none;" id="secondaryPrint" class="secondaryToolbarButton print visibleMediumView" title="Print" tabindex="53" data-l10n-id="print">
              <span data-l10n-id="print_label">Print</span>
            </button>

            <button style="display:none;" id="secondaryDownload" class="secondaryToolbarButton download visibleMediumView" title="Download" tabindex="54" data-l10n-id="download">
              <span data-l10n-id="download_label">Download</span>
            </button>

            <a href="#" id="secondaryViewBookmark" class="secondaryToolbarButton bookmark visibleSmallView" title="Current view (copy or open in new window)" tabindex="55" data-l10n-id="bookmark">
              <span data-l10n-id="bookmark_label">Current View</span>
            </a>

            <div class="horizontalToolbarSeparator visibleLargeView"></div>

            <button id="firstPage" class="secondaryToolbarButton firstPage" title="Go to First Page" tabindex="56" data-l10n-id="first_page">
              <span data-l10n-id="first_page_label">Go to First Page</span>
            </button>
            <button id="lastPage" class="secondaryToolbarButton lastPage" title="Go to Last Page" tabindex="57" data-l10n-id="last_page">
              <span data-l10n-id="last_page_label">Go to Last Page</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="pageRotateCw" class="secondaryToolbarButton rotateCw" title="Rotate Clockwise" tabindex="58" data-l10n-id="page_rotate_cw">
              <span data-l10n-id="page_rotate_cw_label">Rotate Clockwise</span>
            </button>
            <button id="pageRotateCcw" class="secondaryToolbarButton rotateCcw" title="Rotate Counterclockwise" tabindex="59" data-l10n-id="page_rotate_ccw">
              <span data-l10n-id="page_rotate_ccw_label">Rotate Counterclockwise</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="cursorSelectTool" class="secondaryToolbarButton selectTool toggled" title="Enable Text Selection Tool" tabindex="60" data-l10n-id="cursor_text_select_tool">
              <span data-l10n-id="cursor_text_select_tool_label">Text Selection Tool</span>
            </button>
            <button id="cursorHandTool" class="secondaryToolbarButton handTool" title="Enable Hand Tool" tabindex="61" data-l10n-id="cursor_hand_tool">
              <span data-l10n-id="cursor_hand_tool_label">Hand Tool</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="scrollVertical" class="secondaryToolbarButton scrollModeButtons scrollVertical toggled" title="Use Vertical Scrolling" tabindex="62" data-l10n-id="scroll_vertical">
              <span data-l10n-id="scroll_vertical_label">Vertical Scrolling</span>
            </button>
            <button id="scrollHorizontal" class="secondaryToolbarButton scrollModeButtons scrollHorizontal" title="Use Horizontal Scrolling" tabindex="63" data-l10n-id="scroll_horizontal">
              <span data-l10n-id="scroll_horizontal_label">Horizontal Scrolling</span>
            </button>
            <button id="scrollWrapped" class="secondaryToolbarButton scrollModeButtons scrollWrapped" title="Use Wrapped Scrolling" tabindex="64" data-l10n-id="scroll_wrapped">
              <span data-l10n-id="scroll_wrapped_label">Wrapped Scrolling</span>
            </button>
            <!-- $FB: bookflip button -->
            <button id="bookFlip" class="secondaryToolbarButton scrollModeButtons bookFlip" title="Use Book Flip" tabindex="65" data-l10n-id="book_flip">
              <span data-l10n-id="book_flip_label">Book Flip</span>
            </button>

            <div class="horizontalToolbarSeparator scrollModeButtons"></div>

            <button id="spreadNone" class="secondaryToolbarButton spreadModeButtons spreadNone toggled" title="Do not join page spreads" tabindex="66" data-l10n-id="spread_none">
              <span data-l10n-id="spread_none_label">No Spreads</span>
            </button>
            <button id="spreadOdd" class="secondaryToolbarButton spreadModeButtons spreadOdd" title="Join page spreads starting with odd-numbered pages" tabindex="67" data-l10n-id="spread_odd">
              <span data-l10n-id="spread_odd_label">Odd Spreads</span>
            </button>
            <button id="spreadEven" class="secondaryToolbarButton spreadModeButtons spreadEven" title="Join page spreads starting with even-numbered pages" tabindex="68" data-l10n-id="spread_even">
              <span data-l10n-id="spread_even_label">Even Spreads</span>
            </button>

            <div class="horizontalToolbarSeparator spreadModeButtons"></div>

            <button id="documentProperties" class="secondaryToolbarButton documentProperties" title="Document Properties…" tabindex="69" data-l10n-id="document_properties">
              <span data-l10n-id="document_properties_label">Document Properties…</span>
            </button>
          </div>
        </div> 

        <div class="toolbar">
          <div id="toolbarContainer">
            <div id="toolbarViewer">
              <div id="toolbarViewerLeft">
                <button style="display:none" id="sidebarToggle" class="toolbarButton" title="Toggle Sidebar" tabindex="11" data-l10n-id="toggle_sidebar">
                  <span data-l10n-id="toggle_sidebar_label">Toggle Sidebar</span>
                </button>
                <div class="toolbarButtonSpacer"></div>
                {{-- DropDown Button Start --}}
    {{-- <div class="dropdown">
      <button onclick="myFunction()" class="dropbtn">Users</button>
      <div id="myDropdown" class="dropdown-content"> --}}
        
        @if(Auth::user()->roles[0]->id==1)
         
        
        <button
        style="  background-color:#437ac8;
color:white;
        padding: 3px;
        font-size: 14px;
        border: white;
          font-weight: bold;

        margin:3px;
        cursor: pointer;"
         id="sharemeeting" data-id= {{$id}} class="btn btn-secondary top-btn">Start Share</button>





         <button
        style="  background-color:#ceaf20;
color:white;
        padding: 3px;
        font-size: 14px;
        border: white;
  font-weight: bold;
display:none;
        margin:3px;
        cursor: pointer;"
         onclick="location.href = '/StopShare/{{ $id }}';" id="stopmeeting" class="btn btn-secondary top-btn">Stop Share</button>
   





         
        
        
        
        
        
        <a style="
          background-color: #437ac8;
         color: white;
         padding: 3px;
  font-weight: bold;

         font-size: 14px;
         margin:3px;
         border: white;
         cursor: pointer;" class="btn btn-secondary top-btn" name="cars" id="users" >
       
       TotalUsers: <label id = "GFG">
        0
    </label>
      
      </a>
            <button
        style="  background-color:#ceaf20;
        color:white;
        padding: 3px;
        font-size: 14px;
        border: white;
        font-weight: bold;
        margin:3px;
        cursor: pointer;"
         onclick="location.href = '/StopShare/{{ $id }}';" id="home1" class="btn btn-secondary top-btn">Home</button>
    
        
        
       
        
        @endif
       
      
      {{-- </div>
    </div>  --}}
    @if(Auth::user()->roles[0]->id==2)
    
    
    
     <button
        style="  background-color:#ceaf20;
color:white;
        padding: 3px;
        font-size: 14px;
        border: white;
  font-weight: bold;

        margin:3px;
        cursor: pointer;"
         onclick="location.href = '/home'" id="home1" class="btn btn-secondary top-btn">Back</button>
    
         <button
        style="  background-color:#ceaf20;
color:white;
display:none;
        padding: 3px;
  font-weight: bold;

        font-size: 14px;
        border: white;
        margin:3px;
        cursor: pointer;"
         data-id="{{ $id }}" id="reads" class="btn btn-secondary" onclick="webViewerread()" >LEGGI</button>
            
            
         <button
        style="  background-color:#437ac8;
color:white;
display:none;
        padding: 3px;
  font-weight: bold;

        font-size: 14px;
        border: white;
        margin:3px;
        cursor: pointer;"
         data-id="{{ $id }}" id="read" class="btn btn-secondary" onclick="webViewerread()" >LEGGI</button>
        
<button
        style="  background-color:#437ac8;
        color:white;
        padding: 3px;
        display:none;
        font-size: 14px;
        border: white;
  font-weight: bold;

        margin:3px;
        cursor: pointer;"
         data-id="{{ $id }}"  id="follow" class="btn btn-secondary top-btn" onclick="webViewerfollow()" >SEGUI</button>
   
        
<button
        style="  background-color:#437ac8;
        color:white;
        padding: 3px;
        display:none;
        font-size: 14px;
        border: white;
  font-weight: bold;

        margin:3px;
        cursor: pointer;"
         data-id="{{ $id }}"  id="follows" class="btn btn-secondary top-btn" onclick="webViewerfollow()" >SEGUI</button>
   
        
         <button
        style=" background-color:grey;
                color:white;
                display:none;
                padding: 3px;
                font-size: 14px;
                border: white;
                font-weight: bold;
                  margin:3px;
                 cursor: pointer;"
         data-id="{{ $id }}"  id="follo" class="btn btn-secondary top-btn"  >SEGUI</button>
    @endif
    

    <div class="dropdown">
      <button style=" background-color:#ceaf20;
              color:white;
              margin:3px;
              font-weight: bold;
              padding: 3px;
              font-size: 14px;
              border: white;
              cursor: pointer;" onclick="myFunction()" id="pdflist" class="btn dropbtn btn-secondary top-btn">Elenco PDF</button>
      <div id="myDropdown" class="dropdown-content">
      <!-- <select style=" background-color:#ceaf20;
              color:white;
              margin:3px;
              font-weight: bold;
              padding: 3px;
              font-size: 14px;
              border: white;
              cursor: pointer;"   onchange="if (this.value) window.location.href=this.value">
    <option value="/">Elenco PDF</option> -->
    @foreach ($attachments as $attachment )

    <!-- <option value="/pdfview/{{ $attachment->file_name }}/{{ $id }}">{{ $attachment->file_name }}</option> -->
        <a href="/pdfview/{{ $attachment->file_name }}/{{ $id }}">{{ $attachment->file_name }}</a>
       
        @endforeach
        <!-- </select>        -->
      
      </div>
    </div>
    <script>
      /* When the user clicks on the button, 
      toggle between hiding and showing the dropdown content */
      function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
      }
      
      // Close the dropdown if the user clicks outside of it
      window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
          var dropdowns = document.getElementsByClassName("dropdown-content");
          var i;
          for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
              openDropdown.classList.remove('show');
            }
          }
        }
      }
      </script>
                     {{-- DropDown Button End --}}
                <button id="viewFind" class="toolbarButton" title="Find in Document" tabindex="12" data-l10n-id="findbar">
                  <span data-l10n-id="findbar_label">Find</span>
                </button>
                <div class="splitToolbarButton hiddenSmallView">
                  <button class="toolbarButton pageUp" title="Previous Page" id="previous" tabindex="13" data-l10n-id="previous">
                    <span data-l10n-id="previous_label">Previous</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button class="toolbarButton pageDown" title="Next Page" id="next" tabindex="14" data-l10n-id="next">
                    <span data-l10n-id="next_label">Next</span>
                  </button>
                </div>
                <input type="number" id="pageNumber" class="toolbarField pageNumber" title="Page" value="1" size="4" min="1" tabindex="15" data-l10n-id="page">
                <span id="numPages" class="toolbarLabel"></span>
              </div>
              <div id="toolbarViewerRight">
                <button id="presentationMode" class="toolbarButton presentationMode hiddenLargeView" title="Switch to Presentation Mode" tabindex="31" data-l10n-id="presentation_mode">
                  <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
                </button>

                <button id="openFile" Style="display: none;"class="toolbarButton openFile hiddenLargeView" title="Open File" tabindex="32" data-l10n-id="open_file">
                  <span data-l10n-id="open_file_label">Open</span>
                </button>

                <button id="print" Style="display: none;" class="toolbarButton print hiddenMediumView" title="Print" tabindex="33" data-l10n-id="print">
                  <span data-l10n-id="print_label">Print</span>
                </button>

                <button id="download" style="display:none;"  class="toolbarButton download hiddenMediumView" title="Download" tabindex="34" data-l10n-id="download">
                  <span data-l10n-id="download_label">Download</span>
                </button>
                <a href="#" id="viewBookmark" Style="display: none;" class="toolbarButton bookmark hiddenSmallView" title="Current view (copy or open in new window)" tabindex="35" data-l10n-id="bookmark">
                  <span data-l10n-id="bookmark_label">Current View</span>
                </a>

                <div class="verticalToolbarSeparator hiddenSmallView"></div>

                <button   id="secondaryToolbarToggle" class="toolbarButton" title="Tools" tabindex="36" data-l10n-id="tools">
                  <span  data-l10n-id="tools_label">Tools</span>
                </button>
              </div>
              <div id="toolbarViewerMiddle">
                <div class="splitToolbarButton">
                  <button id="zoomOut" class="toolbarButton zoomOut" title="Zoom Out" tabindex="21" data-l10n-id="zoom_out">
                    <span data-l10n-id="zoom_out_label">Zoom Out</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button id="zoomIn" class="toolbarButton zoomIn" title="Zoom In" tabindex="22" data-l10n-id="zoom_in">
                    <span data-l10n-id="zoom_in_label">Zoom In</span>
                   </button>
                </div>
                <span id="scaleSelectContainer" class="dropdownToolbarButton">
                  <select id="scaleSelect" title="Zoom" tabindex="23" data-l10n-id="zoom">
                    <option id="pageAutoOption" title="" value="auto" selected="selected" data-l10n-id="page_scale_auto">Automatic Zoom</option>
                    <option id="pageActualOption" title="" value="page-actual" data-l10n-id="page_scale_actual">Actual Size</option>
                    <option id="pageFitOption" title="" value="page-fit" data-l10n-id="page_scale_fit">Page Fit</option>
                    <option id="pageWidthOption" title="" value="page-width" data-l10n-id="page_scale_width">Page Width</option>
                    <option id="customScaleOption" title="" value="custom" disabled="disabled" hidden="true"></option>
                    <option title="" value="0.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 50 }'>50%</option>
                    <option title="" value="0.75" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 75 }'>75%</option>
                    <option title="" value="1" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 100 }'>100%</option>
                    <option title="" value="1.25" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 125 }'>125%</option>
                    <option title="" value="1.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 150 }'>150%</option>
                    <option title="" value="2" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 200 }'>200%</option>
                    <option title="" value="3" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 300 }'>300%</option>
                    <option title="" value="4" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 400 }'>400%</option>
                  </select>
                </span>
              </div>
            </div>
            <div id="loadingBar">
              <div class="progress">
                <div class="glimmer">
                </div>
              </div>
            </div>
          </div>
        </div>

        <menu type="context" id="viewerContextMenu">
          <menuitem id="contextFirstPage" label="First Page"
                    data-l10n-id="first_page"></menuitem>
          <menuitem id="contextLastPage" label="Last Page"
                    data-l10n-id="last_page"></menuitem>
          <menuitem id="contextPageRotateCw" label="Rotate Clockwise"
                    data-l10n-id="page_rotate_cw"></menuitem>
          <menuitem id="contextPageRotateCcw" label="Rotate Counter-Clockwise"
                    data-l10n-id="page_rotate_ccw"></menuitem>
        </menu>

        <div id="viewerContainer" tabindex="0">
          <div id="viewer" class="pdfViewer"></div>
        </div>

        <div id="errorWrapper" hidden='true'>
          <div id="errorMessageLeft">
            <span id="errorMessage"></span>
            <button id="errorShowMore" data-l10n-id="error_more_info">
              More Information
            </button>
            <button id="errorShowLess" data-l10n-id="error_less_info" hidden='true'>
              Less Information
            </button>
          </div>
          <div id="errorMessageRight">
            <button id="errorClose" data-l10n-id="error_close">
              Close
            </button>
          </div>
          <div class="clearBoth"></div>
          <textarea id="errorMoreInfo" hidden='true' readonly="readonly"></textarea>
        </div>
      </div> <!-- mainContainer -->

      <div id="overlayContainer" class="hidden">
        <div id="passwordOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <p id="passwordText" data-l10n-id="password_label">Enter the password to open this PDF file:</p>
            </div>
            <div class="row">
              <input type="password" id="password" class="toolbarField">
            </div>
            <div class="buttonRow">
              <button id="passwordCancel" class="overlayButton"><span data-l10n-id="password_cancel">Cancel</span></button>
              <button id="passwordSubmit" class="overlayButton"><span data-l10n-id="password_ok">OK</span></button>
            </div>
          </div>
        </div>
        <div id="documentPropertiesOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <span data-l10n-id="document_properties_file_name">File name:</span> <p id="fileNameField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_file_size">File size:</span> <p id="fileSizeField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_title">Title:</span> <p id="titleField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_author">Author:</span> <p id="authorField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_subject">Subject:</span> <p id="subjectField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_keywords">Keywords:</span> <p id="keywordsField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_creation_date">Creation Date:</span> <p id="creationDateField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_modification_date">Modification Date:</span> <p id="modificationDateField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_creator">Creator:</span> <p id="creatorField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_producer">PDF Producer:</span> <p id="producerField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_version">PDF Version:</span> <p id="versionField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_page_count">Page Count:</span> <p id="pageCountField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_page_size">Page Size:</span> <p id="pageSizeField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_linearized">Fast Web View:</span> <p id="linearizedField">-</p>
            </div>
            <div class="buttonRow">
              <button id="documentPropertiesClose" class="overlayButton"><span data-l10n-id="document_properties_close">Close</span></button>
            </div>
          </div>
        </div>
        <div id="printServiceOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <span data-l10n-id="print_progress_message">Preparing document for printing…</span>
            </div>
            <div class="row">
              <progress value="0" max="100"></progress>
              <span data-l10n-id="print_progress_percent" data-l10n-args='{ "progress": 0 }' class="relative-progress">0%</span>
            </div>
            <div class="buttonRow">
              <button id="printCancel" class="overlayButton"><span data-l10n-id="print_progress_close">Cancel</span></button>
            </div>
          </div>
        </div>
      </div>  <!-- overlayContainer -->

    </div> <!-- outerContainer -->
    <div id="printContainer"></div>
    <input type="hidden" value="{{$id}}" id="meeting_id">
  </body>
</html>

<script>
var stop=1;
 var fol = 0;
 var sop=0;
$('body').on('click', '#sharemeeting', function(e) {
 
  e.preventDefault();
  window.sop=1;
 id = $(this).data('id');
 $('#sharemeeting').hide();
 $('#stopmeeting').show();
 $('#users').show();
//  alert(window.pagenumber);
 $.ajax({
     url: "/StartShare/"+id+"/"+PDFViewerApplication.pdfViewer.currentPageNumber,
     method: 'GET',

     success: function(result) {
       alert('Meeting Shared');
       windows.sop=1;
     
     }
 });
});








$('body').on('click', '#follow', function(e) {
 e.preventDefault();
  $('#bookFlip').click();
  $('#reads').show();
//   $('#follo').show();
  $('#follow').hide();
  $('#pdflist').hide();
 document.getElementById('viewer').style.pointerEvents = 'none';
  window.fol=1;
  window.stop=1;
  const button = document.querySelector('#follow');
  button.disabled = true;

  $('#previous').hide();
  $('#read').hide();
  $('#next').hide();
  $('#pageNumber').hide(); 
  $('#numPages').hide();

alert('Followed');
 
 $.ajax({
     url: "/ClickFollow/"+id,
     method: 'GET',
     success: function(result) {
       
     
     }
 });
});






$('body').on('click', '#reads', function(e) {
  e.preventDefault();
  document.getElementById('viewer').style.pointerEvents = 'auto'; 
  const button = document.querySelector('#follow');
  button.disabled = false;

  $('#pdflist').show();
  $('body').addClass("start-scrolling");
  $('#bookFlip').click();
  $('#previous').show();
  $('#next').show();
  $('#pageNumber').show();
  $('#numPages').show();
$('#follow').show();
$('#reads').hide();
 id = $(this).data('id');


      
       $.ajax({
     url: "/UnFollow/"+id,
     method: 'GET',
     
     success: function(result) {
      alert('UnFollowed')
     window.fol=0;
     }
 });


 $.ajax({
     url: "/Follow/"+id,
     method: 'GET',
     success: function(result) {
     alert('Read Mode');
     t=parseInt(result);
     PDFViewerApplication.pdfViewer.currentPageNumber=t;
     
     
       
       
 
     
     }
 });





});

$('body').on('click', '#read', function(e) {
  e.preventDefault();
  document.getElementById('viewer').style.pointerEvents = 'auto'; 
  const button = document.querySelector('#follow');
  button.disabled = false;
  $('#reads').hide();
  if(window.stop!=1)
            {
  $('#follow').show();
  $('#read').hide();
            }
//   $('#read').show();
  $('body').addClass("start-scrolling");
  $('#bookFlip').click();
  $('#previous').show();
  $('#pdflist').show();
  $('#next').show();
  $('#pageNumber').show();
  $('#numPages').show();
  id = $(this).data('id');
 $.ajax({
     url: "/Follow/"+id,
     method: 'GET',
     success: function(result) {
       alert('Read Mode');
     t=parseInt(result);
      PDFViewerApplication.pdfViewer.currentPageNumber=t;
       $('#pageNumber').val(t).trigger('change');
       window.fol=0;
       
       
       
       $.ajax({
     url: "/UnFollow/"+id,
     method: 'GET',
     
     success: function(result) {
      alert('UnFollowed')
     
     }
 });

     
     }
 });

});



window.onload =function meetingstart(){
id= $('#meeting_id').val();
 $.ajax({
     url: "/startsharings/"+id,
     method: 'GET',
    
     success: function(s) {
      if(s['start'] ===true)
      {
        if(fol===0)
        {
          const button = document.querySelector('#follow');
          button.disabled = false;
  
        $('#follow').show();
        // $('#follo').hide();
        }
        
      }
      else{
        if(fol===1)
        {
        $('#follow').hide();
        $('#pdflist').hide();
        // $('#follo').show();
        }
        
        
        if(s['stop']===false && s['start']===false && fol===1)
        {
            
            if(window.stop===1)
            {
            alert("Meeting-Stoped By Admin")
            $('#home1').click();
            $('#read').hide();
            $('#reads').hide();
            $('#follow').hide();

            
            window.stop=0;
            
            }
            $('#follow').hide();
            $('#read').hide();
            
 document.getElementById('viewer').style.pointerEvents = 'auto'; 
  $('#reads').hide();
  $('body').addClass("start-scrolling");
  $('#bookFlip').click();
  $('#previous').show();
  $('#next').show();
  $('#pageNumber').show();
  $('#numPages').show();
   $('#read').show();
   window.fol=0;
            
        }}
     }
 });

 setTimeout(meetingstart, 5000);
 
}();



window.onload =function pageupdate(){
id= $('#meeting_id').val();

 if(sop===1)
 {
 $.ajax({
     url: "/StartShare/"+id+"/"+PDFViewerApplication.pdfViewer.currentPageNumber+"/"+window.pdfname,
     method: 'GET',
     success: function(result) {
     }
 });
}

if(fol===1)

{
  $.ajax({
     url: "/Follow/"+id,
     method: 'GET',
     success: function(result) {
      window.t=parseInt(result.pagenumber);
    //   alert(window.pagenumber)
     sa=result.pagenumber;
     if(PDFViewerApplication.pdfViewer.currentPageNumber!=t && t!=1)
     {
        //  alert(t);
      PDFViewerApplication.pdfViewer.currentPageNumber=result.pagenumber-1;
     $('#next').click(); 
     
     }
     if(t===1)
     {
        //  alert(t);
      PDFViewerApplication.pdfViewer.currentPageNumber=1;
     
     }
    
     if(result.pdfname!=window.pdfname) 
     {
       
       for (i = 0; i <= 1; i++)
{
      $.ajax({
     url: "/UnFollow/"+id,
     method: 'GET',
     success: function(result) {
      alert('Pdf Changed') 
     }
 });
       window.location.href ="/pdfview/"+result.pdfname+"/"+ id ;
}
      }
    
     document.getElementById('viewer').style.pointerEvents = 'auto';

     }
 });

}



$.ajax({
     url: "/userupdate/"+id,
     method: 'GET',

     success: function(result) {
       document.getElementById('GFG').innerHTML
                = result;
     
     }
 });


setTimeout(pageupdate, 2000);
 
}();
</script>
