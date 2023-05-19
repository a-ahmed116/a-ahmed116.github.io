<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.6.172/web/pdf_viewer.min.css" rel="stylesheet">
</head>
<body>

<div id="pdf-contents">
    <div id="pdf-meta" style="display: flex;margin: 10px;">
        <div id="pdf-buttons" style="margin-right: 10px;">
            <button id="pdf-prev">Previous</button>
            <button id="pdf-next">Next</button>
        </div>
        <div id="page-count-container" style="display: flex;">
            Page <div id="pdf-current-page" style="margin: 0 3px;"></div> of <div id="pdf-total-pages" style="margin: 0 3px;"></div>
        </div>
    </div>
    <canvas id="the-canvas" style="border: 1px solid; border-radius: 12px"></canvas>
    <div id="page-loader">Loading page ...</div>
</div>


<script>
    window.onload = function(){
        var pdfDoc, __CURRENT_PAGE = 1, __TOTAL_PAGES;
        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        var url = './sample-protected.pdf';

        // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];

        // The workerSrc property shall be specified.
        pdfjsLib.GlobalWorkerOptions.workerSrc = '//cdn.jsdelivr.net/npm/pdfjs-dist@3.6.172/build/pdf.worker.js';

        // Asynchronous download of PDF
        var loadingTask = pdfjsLib.getDocument({url: url, password: "*A14M#&5&!@bU7mV"});
        loadingTask.promise.then(function(pdf) {
            pdfDoc = pdf;
            __TOTAL_PAGES = pdfDoc.numPages;

            console.log('PDF loaded');

            $("#pdf-loader").hide();
            $("#pdf-contents").show();
            $("#pdf-total-pages").text(__TOTAL_PAGES);
            
            // Fetch the first page
            showPage(1);
        }, function (reason) {
            $("#pdf-loader").hide();

            // PDF loading error
            console.error(reason);
        });
 
        function showPage(page_no) {
            
            // Disable Prev & Next buttons while page is being loaded
            $("#pdf-next, #pdf-prev").attr('disabled', 'disabled');

            // While page is being rendered hide the canvas and show a loading message
            $("#pdf-canvas").hide();
            $("#page-loader").show();

            // Update current page in HTML
            $("#pdf-current-page").text(page_no);
            
            // Fetch the page
            pdfDoc.getPage(page_no).then(function(page) {
                console.log('Page loaded');
                
                var scale = 1.5;
                var viewport = page.getViewport({scale: scale});

                // Prepare canvas using PDF page dimensions
                var canvas = document.getElementById('the-canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);
                renderTask.promise.then(function () {
                    $("#pdf-next, #pdf-prev").removeAttr('disabled');
                    $("#pdf-canvas").show();
                    $("#page-loader").hide();
                    console.log('Page rendered');
                });
            });
        }

        $("#pdf-prev").on('click', function() {
            if(__CURRENT_PAGE != 1)
                showPage(--__CURRENT_PAGE);
        });

        // Next page of the PDF
        $("#pdf-next").on('click', function() {
            if(__CURRENT_PAGE != __TOTAL_PAGES)
                showPage(++__CURRENT_PAGE);
        });
    }
</script>
    
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.6.172/build/pdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</body>
</html>