import webview

if __name__ == '__main__':
    window = webview.create_window(
        '.:: Sofrehpardaz  |  سیستم آشپز ::.',
        'http://localhost/sofrehpardaz/chef_login.php',

       width=999900, 
       height=999900,
       resizable=False,
       x=-10,
       y=0, 
       


    )
    webview.start(gui='edgechromium')
