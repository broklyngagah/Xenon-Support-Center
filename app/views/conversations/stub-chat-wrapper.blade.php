<div id="xenon-wrapper">
    <div class="panel-xenon panel-success-xenon">

        <div class="panel-heading-xenon" data-toggle="collapse" data-parent="#accordion-color" href="#xenon-widget">
            <h6 id="xenon-widget-title" class="panel-title-xenon">Contact Us - Offline</h6>
        </div>

        <div id="xenon-widget" class="panel-collapse collapse">

            <div class="panel-body-xenon">

                <div id="xenon-form-view">
                    <p>At this moment there are no logged members, but you can leave your messages.</p>

                    <div style="display: none;" id="xenon-errors" class="alert alert-danger"></div>
                    <div style="display: none;" id="xenon-success" class="alert alert-success"></div>

                    <div class="form-group">
                        <label for="xenon-form-name">Name</label>
                        <input id="xenon-form-name" type="text" class="form-control" placeholder="Name (Required)">
                    </div>

                    <div class="form-group">
                        <label for="xenon-form-email">Email</label>
                        <input id="xenon-form-email" type="text" class="form-control" placeholder="Email (Required)">
                    </div>

                    <div class="form-group">
                        <label for="xenon-form-department">Department</label>
                        <select id="xenon-form-department" class="form-control"></select>
                    </div>

                    <div class="form-group">
                        <label for="xenon-form-message">Message</label>
                        <textarea id="xenon-form-message" name="message" class="form-control" placeholder="Message (Required)"></textarea>
                    </div>

                    <div class="form-group">
                        <button id="xenon-start" type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>

                <div id="xenon-chat-view" style="display: none;">
                    <ul class="chat"></ul>
                </div>

            </div>

            <div id="xenon-chat-footer" class="panel-footer-xenon" style="display: none;">
                <div class="input-group">
                    <textarea placeholder="Type your message here..." style="height:30px;" id="xenon-message" class="form-control input-sm"></textarea>
                        <span class="input-group-btn">
                            <button id="xenon-message-send" class="btn btn-warning btn-sm" id="btn-chat">Send</button>
                        </span>
                </div>
                <button id="xenon-end" style="margin-top:2px;" class="btn btn-danger btn-block" id="btn-chat">End Chat</button>
            </div>

        </div>

    </div>
</div>