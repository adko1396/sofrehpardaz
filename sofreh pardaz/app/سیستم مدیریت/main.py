import webview

if __name__ == '__main__':
    window = webview.create_window(
        '.:: Sofrehpardaz  |  سیستم مدیریت ::.',
        'http://localhost/sofrehpardaz/manager_login.php',

       width=999900, 
       height=999900,
       resizable=False,
       x=-10,
       y=0, 
       


    )
    webview.start(gui='edgechromium')
