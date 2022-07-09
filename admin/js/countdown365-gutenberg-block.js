

( function ( blocks, element ) {
    var el = element.createElement;
   
    var tomorrow = new Date( new Date().getTime() + 24 * 60 * 60 * 1000 );
    var dd = String(tomorrow.getDate()).padStart(2, '0');
    var mm = String(tomorrow.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = tomorrow.getFullYear();
    tomorrow = yyyy + '/' + mm + '/' + dd ;
    var languagesList ={ "English":"English",
                       "spanish":"Spanish",
                       "Finnish":"Finnish",
                       "French":"French",
                       "Italian":"Italian",
                       "Latvian":"Latvian",
                       "Dutch":"Dutch",
                       "Norwegian":"Norwegian",
                       "Portuguese":"Portuguese",
                       "Russian":"Russian",
                       "Swedish":"Swedish",
                       "Bangla":"Bangla",
                       "Chinese":"Chinese"
                      
                   };

    var faceModes =  { "DailyCounter":" Days : Hours : Minutes : Seconds",
                       "HourlyCounter":"Hours : Minutes : Seconds",
                       "MinuteCounter":"Minutes : Seconds"                   
                      
                   };

    var labelHide =  { "show":"Show",
                       "hide":"Hide",                                  
                   };

    blocks.registerBlockType( 'vuiphor/countdown365', {
    	title: 'Countdown365: Ultimate Countdown',
		icon: 'clock', 
		category: 'common',
		
        attributes: {

            title:{
                type: 'string',
                value: "",
                default: ""
            },
            language:{
                type: 'string',
                value: "English",
                default: "English"
            },  

            face:{
                type: 'string',
                value: "DailyCounter",
                default: "DailyCounter",
            },
            label_show:{
                type: 'string',
                value: "show",
                default: "show"
            },
            
            date:{
                type: 'string',
                value: tomorrow,
                default:tomorrow
            },

            bg:{
                type: 'string',
                value: "#dd3333",
                default: "#dd3333"
            },

            label_color:{
                type: 'string',
                value: "#000000",
                default: "#000000"
            },

            font_color:{
                type: 'string',
                value: "#ffffff",
                default: "#ffffff"
            },

            separator_color:{
                type: 'string',
                value: "#3DA8CC",
                default: "#3DA8CC"
            },
           
            font_size:{
                type: 'parseInt',
                value: "36",
                default: "36"
            },
            border_radius:{
                type: 'parseInt',
                value: "5",
                default: "5"
            },
            digit_gap:{
                type: 'parseInt',
                value: "2",
                default: "2"
            },
            dot_size:{
                type: 'parseInt',
                value: "4",
                default: "4"
            },

            message:{
                type: 'string',
                value: "",
                default: ""
            },

        },
        edit: function (props) {
            
            children = [];
            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Title')  );
            children.push( countdown365input("title","Countdown Title") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Language')  );
            children.push( countdown365_lb_simple_select("language",languagesList) );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Face')  );
            children.push( countdown365_lb_simple_select("face",faceModes) );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Label')  );
            children.push( countdown365_lb_simple_select("label_show",labelHide) );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Target Date')  );
            children.push( countdown365inputDate("date","Date") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Background Color')  );
            children.push( countdown365inputColor("bg","Background Color") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Label Font Color')  );
            children.push( countdown365inputColor("label_color","Countdown Label Font Color") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Digit Font Color')  );
            children.push( countdown365inputColor("font_color","Countdown Digit Font Color") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Separator Color')  );
            children.push( countdown365inputColor("separator_color","Countdown Separator Color") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Font Size')  );
            children.push( countdown365inputNumber("font_size",'Countdown Font Size') );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Border Radius')  );
            children.push( countdown365inputNumber("border_radius",'Countdown Border Radius') );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Digit Gap')  );
            children.push( countdown365inputNumber("digit_gap","Countdown Digit Gap") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Dot Size')  );
            children.push( countdown365inputNumber("dot_size","Countdown Dot Size") );

            children.push( el('div',{className:'countdown365_sb_label'}, 'Countdown Message')  );
            children.push( countdown365inputTextarea("message","Countdown Message") );
           
           
           function countdown365_lb_simple_select(element_name,options_list){
                var created_options = new Array();
                for(var key in options_list) {
                    selected_option=false;
                    if(props.attributes[element_name]==key){
                        selected_option=true;
                    }
                    created_options.push(el('option',{value:''+key+'',selected:selected_option},options_list[key]))
                }
                return el( 'select', { className:'' , onChange: function( value ) {var select=value.target; var params={};  params[element_name]=select.options[select.selectedIndex].value;  props.setAttributes( params)}},created_options);
                                   
                         
            }
           
            function countdown365input(element_name,element_title ){
                return el('input',{ type:"text",Value:props.attributes[element_name],className:'',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}}) ;                                                   
            }

            function countdown365inputColor(element_name,element_title ){
                return el('input',{ type:"color",Value:props.attributes[element_name],className:'',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}}) ;                                                   
            }

            function countdown365inputDate(element_name,element_title ){
                return el('input',{ type:"text",Value:props.attributes[element_name],className:'countdown365_shortcode_block_date ',onSelect: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}}) ;                                                   
            }

            function countdown365inputNumber(element_name,element_title ){
                return el('input',{ type:"number",Value:props.attributes[element_name],className:'',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}}) ;                                                   
            }

            function countdown365inputTextarea(element_name,element_title ){
                return el('textarea',{ type:"text",Value:props.attributes[element_name],className:'',onChange: function( value ) {var select=value.target; var params={}; params[element_name]=select.value;  props.setAttributes(params)}}) ;                                                   
            }

            return el(
                'form',
                { id:"countdown365_shortcode_block", class:"countdown365_shortcode_block"},
                children
            );
        },
        save: function (props) {
            var shortcode_atributes="";
            shortcode_atributes = shortcode_atributes + 'title="' + props.attributes.title + '" ';
            shortcode_atributes = shortcode_atributes + 'date="' + props.attributes.date + '" ';
            shortcode_atributes = shortcode_atributes + 'language="' + props.attributes.language + '" ';
            shortcode_atributes = shortcode_atributes + 'face="' + props.attributes.face + '" ';
            shortcode_atributes = shortcode_atributes + 'label="' + props.attributes.label_show + '" ';
            shortcode_atributes = shortcode_atributes + 'background_color="' + props.attributes.bg + '" ';
            shortcode_atributes = shortcode_atributes + 'label_color="' + props.attributes.label_color + '" ';
            shortcode_atributes = shortcode_atributes + 'font_color="' + props.attributes.font_color + '" ';
            shortcode_atributes = shortcode_atributes + 'separator_color="' + props.attributes.separator_color + '" ';
            shortcode_atributes = shortcode_atributes + 'font_size="' + props.attributes.font_size + '" ';
            shortcode_atributes = shortcode_atributes + 'border_radius="' + props.attributes.border_radius + '" ';
            shortcode_atributes = shortcode_atributes + 'digit_gap="' + props.attributes.digit_gap + '" ';
            shortcode_atributes = shortcode_atributes + 'dot_size="' + props.attributes.dot_size + '" ';
            shortcode_atributes = shortcode_atributes + 'message="' + props.attributes.message + '" ';
            return "[countdown365 "+ shortcode_atributes +"]";
           
        },
    } );
} )( window.wp.blocks, window.wp.element );


