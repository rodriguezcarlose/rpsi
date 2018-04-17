function toastMessage(title, content, type, callback, __CloseModal) {

    title = (title && '<strong>' + title + '</strong>') || '';
    content = content || '';

    var
        separator = (content && title) && ' &#183; ' || '',
        $closeAlert = $('<a>').addClass('close'),
        $alert = $('<div>').addClass('alert-box').attr('data-alert', '').html(title + separator +content).append($closeAlert),
        $toastArea = $('#toastArea'),
        $toastActive = $toastArea.find('[data-alert]'),
        alertHeight = $alert.prependTo($toastArea).outerHeight(),
        toastAreaTop = parseInt($toastArea.css('top')),
        totalTime = 5500,
        intervalTime = 100,
        cicles = totalTime/intervalTime,
        deferred = $.Deferred(),
        interval, $reveal,
        initExpireToast = function(){
        interval = setInterval(function(){

            deferred.notify(cicles);

            if(!--cicles) deferred.resolve();

        }, intervalTime);
        };

    if ((!!__CloseModal === true) && ($reveal = $('.reveal-modal.open')).length > 0) $reveal.foundation('reveal', 'close');

    switch (type) {
        case 1:
            $alert.addClass('success');
            break;
        case 2:
            $alert.addClass('warning');
            break;
        case -1:
            $alert.addClass('alert');
            break;
        default:
            break;
    }

    if ($toastActive.length > 3) {
        $toastActive.slice(3).find('a.close').click();
    }

    $toastArea.foundation('alert', 'reflow');

    $alert
        .css({ marginTop: -(alertHeight + toastAreaTop), marginBottom: toastAreaTop })
        .animate({ marginTop: 0, marginBottom: 10 }, 'fast')
        .on('mouseenter.fndtn.alert', function() { clearInterval(interval); })
        .on('mouseleave.fndtn.alert', function(){ initExpireToast(); });

    $closeAlert.off('click.fndtn.alert').on('click.fndtn.alert', function () {
        $alert
            .stop(true)
            .animate({ opacity: 0 }, 100)
            .delay(150)
            .animate({ height: 0 }, 300)
            .queue(function() { $alert.remove() });
    });

    deferred.always(function(){ $closeAlert.click(); clearInterval(interval); });

    if( Modernizr.canvas ) createCountdownCanvas($alert, $closeAlert, deferred.promise());

    if (callback instanceof Function) callback();

    initExpireToast();

}

function createCountdownCanvas($alert, $container, deferred){

  var
  canvas = $('<canvas>').attr({ width:30, height:30 }).appendTo($alert).get(0),
  ctx = canvas.getContext("2d"),
  endAngle = 2*Math.PI,
  startAngle = 0,
  radius = canvas.height / 2,
  color = "#9d9d9d",
  part;

  deferred
  .progress(function(cicles){

    part = endAngle/cicles;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.beginPath();
    ctx.arc(radius,radius,radius-1,startAngle,endAngle-=part);
    ctx.lineWidth = 1;
    ctx.strokeStyle = color;
    ctx.stroke();

  })
  .done(function(){

    canvas.parentElement.removeChild(canvas);

  });

};