function generatejquery(url)
{
    //console.log("ABCD");
    
    $(document).ready(function () {

            var search = $(".chintantablesearch").val();
            var pageno = 1;
            var orderby = "";
            var orderorder = "";
            var maxrow = 20;
            $(".drawchintantable .maxrow").val(maxrow);
            
			$(".chintantablesearch").keyup(function(event) {
			
				if(event.which==13)
				{
					$(".chintantablesearchgo").click();
				}
				
			});

            function fillchintandata() {
                $(".drawchintantable .loader").show();
                $.getJSON(url, {
                    search: search,
                    pageno: pageno,
                    orderby: orderby,
                    orderorder: orderorder,
                    maxrow:maxrow
                }, function (data) {

                    $(".drawchintantable table tbody").html("");
                    var result = data.queryresult;
                    for (var i = 0; i < result.length; i++) {
                        $(".drawchintantable table tbody").append(drawtable(result[i]));
                    }

                    
                    $(".data-totalvalues").text(data.totalvalues);
                    $(".data-totalpagenumbers").text(data.lastpage);



                    $(".chintantablepagination ul.pagination").html("");
                    $(".chintantablepagination .pagenoinput").remove();
                    
                    if (data.pageno != 1) {
                        $(".chintantablepagination ul.pagination").append('<li><a href="#" data-page="' + (data.pageno - 1) + '"><span aria-hidden="true">&laquo;</span></a></li>');
                    } else {
                        $(".chintantablepagination ul.pagination").append('<li class="disabled"><a href="#" data-page="' + (data.pageno) + '"><span aria-hidden="true">&laquo;</span></a></li>');
                    }

                    var maxbuffer=2
                    var startbuff=(data.pageno-1)-maxbuffer;
                    var endbuff=data.pageno+maxbuffer;
                    
                    if(startbuff<0)
                    {
                        startbuff=0;
                    }
                    if(endbuff>data.lastpage)
                    {
                        endbuff=data.lastpage;
                    }
                    
                    
                    for (var i = startbuff; i < endbuff; i++) {
                        if ((i + 1) == data.pageno)
                            $(".chintantablepagination ul.pagination").append('<li class="active" ><a href="#" data-page="' + (i + 1) + '">' + (i + 1) + '</a>');
                        else
                            $(".chintantablepagination ul.pagination").append('<li><a href="#" data-page="' + (i + 1) + '">' + (i + 1) + '</a>');
                    }
                    
                    
                    if (data.pageno != data.lastpage) {
                        $(".chintantablepagination ul.pagination").append('<li><a href="#" data-page="' + (data.pageno + 1) + '"><span aria-hidden="true">&raquo;</span></a></li>');
                    } else {
                        $(".chintantablepagination ul.pagination").append('<li class="disabled"><a href="#" data-page="' + (data.pageno) + '"><span aria-hidden="true">&raquo;</span></a></li>');
                    }

                    
                    $(".chintantablepagination").append("<div class='pagenoinput' style='float:right;width: 146px;'><input type='number' min='1' max='"+data.lastpage+"' placeholder='Page no.' class='form-control pagenotovisit' style='width: 100px;float: left;'/> <a href='#' class='pagetovisitbutton btn btn-default'>Go</a></div>");
                    
                    
                    $(".chintantablepagination ul.pagination li a").click(function () {
                        pageno = parseInt($(this).attr("data-page"));
                        fillchintandata();
                        return false;

                    });
                    
                    $(".chintantablepagination .pagenoinput a.pagetovisitbutton").click(function () {
                        pageno = parseInt($('.chintantablepagination .pagenoinput input').val());
                        fillchintandata();
                        return false;

                    });
                    
                    var allpages=$(".chintantablepagination ul.pagination li a");
                    var totalwidth=0;
                    console.log("Length: "+allpages.length);
                    for(var i=0;i<allpages.length;i++)
                    {
                        totalwidth+=$(allpages).eq(i).width()+26;
                    }
                    $(".chintantablepagination ul.pagination").width(totalwidth);
                    $(".chintantablepagination").css({"overflow-x": "scroll","height": "72px","overflow-y": "hidden"});

                    for (var i = 0; i < data.elements.length; i++) {
                        var element = data.elements[i];
                        $(".drawchintantable thead tr th[data-field='" + element.alias + "']").html(element.header);

                        if (element.sort == "ASC") {
                            $(".drawchintantable thead tr th[data-field='" + element.alias + "']").append("<button data-sort='DESC' class='pull-right btn btn-xs sortbutton'><span class='glyphicon glyphicon-chevron-up'></span></button>");
                        } else if (element.sort == "DESC") {
                            $(".drawchintantable thead tr th[data-field='" + element.alias + "']").append("<button data-sort='ASC' class='pull-right btn btn-xs sortbutton'><span class='glyphicon glyphicon-chevron-down'></span></button>");
                        } else if (element.sort == "1") {
                            $(".drawchintantable thead tr th[data-field='" + element.alias + "']").append("<button data-sort='ASC' class='pull-right btn btn-xs sortbutton'><span class='glyphicon glyphicon-tasks'></span></button>");
                        }
                    }

                    $(".drawchintantable thead tr th button.sortbutton").click(function () {
                        console.log("Clicked");
                        orderby = $(this).parents("th").attr("data-field");
                        orderorder = $(this).attr("data-sort");
                        maxrow=$(".drawchintantable .maxrow").val();
                        fillchintandata();
                    });


                    $(".drawchintantable .loader").hide();

                });




            };

            $(".chintantablesearchgo").click(function () {
                search = $(".chintantablesearch").val();
                pageno = 1;
                maxrow=$(".drawchintantable .maxrow").val();
                fillchintandata();
            });

            $(".chintantablesearchgo").click(function () {
                search = $(".chintantablesearch").val();
                pageno = 1;
                maxrow=$(".drawchintantable .maxrow").val();
                fillchintandata();
            });
            $(".drawchintantable .maxrow").change(function () {
                search = $(".chintantablesearch").val();
                pageno = 1;
                maxrow=$(".drawchintantable .maxrow").val();
                fillchintandata();
            });



            fillchintandata();
        
        
        
        
            


        });
}



function generatepiechart(texttitle, target, value) {
    $(target).highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1, //null,
            plotShadow: false
        },
        title: {
            text: texttitle
        },
        tooltip: {
            pointFormat: '<b>{point.name}</b>:{point.y}  ({point.percentage:.1f} %)'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>:{point.y}  ({point.percentage:.1f} %)',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: value
        }]
    });
}