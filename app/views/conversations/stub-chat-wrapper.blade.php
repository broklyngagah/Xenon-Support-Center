<div id="k15-wrapper">
    <div class="panel panel-success">

        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion-color" href="#k15-widget">
            <h6 id="k15-widget-title" class="panel-title">Contact Us - Offline</h6>
        </div>

        <div id="k15-widget" class="panel-collapse collapse">

            <div class="panel-body">

                <div id="k15-form-view">
                    <p>At this moment there are no logged members, but you can leave your messages.</p>

                    <div style="display: none;" id="k15-errors" class="alert alert-danger"></div>
                    <div style="display: none;" id="k15-success" class="alert alert-success"></div>

                    <div class="form-group">
                        <label for="k15-form-name">Name</label>
                        <input id="k15-form-name" type="text" class="form-control" placeholder="Name (Required)">
                    </div>

                    <div class="form-group">
                        <label for="k15-form-email">Email</label>
                        <input id="k15-form-email" type="text" class="form-control" placeholder="Email (Required)">
                    </div>

                    <div class="form-group">
                        <label for="k15-form-department">Department</label>
                        <select id="k15-form-department" class="form-control"></select>
                    </div>

                    <div class="form-group">
                        <label for="k15-form-message">Message</label>
                        <textarea id="k15-form-message" name="message" class="form-control" placeholder="Message (Required)"></textarea>
                    </div>

                    <div class="form-group">
                        <button id="k15-start" type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>

                <div id="k15-chat-view" style="display: none;">
                    <ul class="chat"></ul>
                </div>

            </div>

            <div id="k15-chat-footer" class="panel-footer" style="display: none;">
                <div class="input-group">
                    <textarea placeholder="Type your message here..." style="height:30px;" id="k15-message" class="form-control input-sm"></textarea>
                        <span class="input-group-btn">
                            <button id="k15-message-send" class="btn btn-warning btn-sm" id="btn-chat">Send</button>
                        </span>
                </div>
                <button id="k15-end" style="margin-top:2px;" class="btn btn-danger btn-block" id="btn-chat">End Chat</button>
            </div>

        </div>

    </div>
</div>