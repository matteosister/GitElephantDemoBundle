jQuery ->
  $('ul.dropdown').css('position', 'absolute')
  $('ul.dropdown li.label').hide()
  $('ul.dropdown').hover(openSubmenu, closeSubmenu)
  top = 0
  left = 0
  rows = 8
  col = 1
  actualRow = 1
  maxWidth = 0
  now = 1
  voices = $('ul.dropdown li:not(.label)')
  for voice in voices
    $(voice).addClass('col-' + col)
    $(voice).css('position', 'absolute')
    $(voice).css('left', '0')
    $(voice).css('background-color', 'white')
    $(voice).css('padding', '5px')
    if top == 0
      top = $(voice).outerHeight()
      $('ul.dropdown').css('margin-bottom', (top + 5) + 'px')
    if $(voice).children('a').hasClass('active')
      $(voice).css('top', 0)
      $(voice).css('border', '1px solid #CCC')
      $(voice).addClass('active')
    else
      $(voice).css('top', top + 'px')
      $(voice).css('left', left + 'px')
      $(voice).css('border-top', '1px solid #CCC')
      $(voice).css('border-left', '1px solid #CCC')
      $(voice).css('border-right', '1px solid #CCC')
      if (now == voices.length)
        $(voice).css('border-bottom', '1px solid #CCC')
      top += $(voice).outerHeight()
      if $(voice).outerWidth() >= maxWidth
            maxWidth = $(voice).outerWidth()
      if actualRow == rows
        $(voice).css('border-bottom', '1px solid #CCC')
        actualRow = 1
        top = 0
        left += maxWidth + 11
        for voice in $('ul.dropdown li:not(.label).col-' + col)
          $(voice).width(maxWidth)
        col++
        maxWidth = 0
      else
        actualRow++
      $(voice).hide()
    now++


openSubmenu = ->
  $('ul.dropdown li:hidden').fadeIn()
closeSubmenu = ->
  $('ul.dropdown li:not(.active)').fadeOut()