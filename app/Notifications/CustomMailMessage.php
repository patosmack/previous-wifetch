<?php

namespace App\Notifications;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\SimpleMessage;
use Traversable;

class CustomMailMessage extends SimpleMessage implements Renderable
{
    /**
     * The view to be rendered.
     *
     * @var array|string
     */
    public $view;

    /**
     * The view data for the message.
     *
     * @var array
     */
    public $viewData = [];

    /**
     * The Markdown template to render (if applicable).
     *
     * @var string|null
     */
    public $markdown = 'notifications::email';

    /**
     * The current theme being used when generating emails.
     *
     * @var string|null
     */
    public $theme;

    /**
     * The "from" information for the message.
     *
     * @var array
     */
    public $from = [];

    /**
     * The "reply to" information for the message.
     *
     * @var array
     */
    public $replyTo = [];

    /**
     * The "cc" information for the message.
     *
     * @var array
     */
    public $cc = [];

    /**
     * The "bcc" information for the message.
     *
     * @var array
     */
    public $bcc = [];

    /**
     * The attachments for the message.
     *
     * @var array
     */
    public $attachments = [];

    /**
     * The raw attachments for the message.
     *
     * @var array
     */
    public $rawAttachments = [];

    /**
     * Priority level of the message.
     *
     * @var int
     */
    public $priority;

    /**
     * The callbacks for the message.
     *
     * @var array
     */
    public $callbacks = [];

    /**
     * Build Table from Markdown.
     *
     * @var array
     */
    public $table = null;

    /**
     * Set the view for the mail message.
     *
     * @param  array|string  $view
     * @param  array  $data
     * @return $this
     */
    public function view($view, array $data = [])
    {
        $this->view = $view;
        $this->viewData = $data;

        $this->markdown = null;

        return $this;
    }

    /**
     * Set the Markdown template for the notification.
     *
     * @param  string  $view
     * @param  array  $data
     * @return $this
     */
    public function markdown($view, array $data = [])
    {
        $this->markdown = $view;
        $this->viewData = $data;

        $this->view = null;

        return $this;
    }

    /**
     * Set the default markdown template.
     *
     * @param  string  $template
     * @return $this
     */
    public function template($template)
    {
        $this->markdown = $template;

        return $this;
    }

    /**
     * Set the theme to use with the Markdown template.
     *
     * @param  string  $theme
     * @return $this
     */
    public function theme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Set the from address for the mail message.
     *
     * @param  string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function from($address, $name = null)
    {
        $this->from = [$address, $name];

        return $this;
    }

    /**
     * Set the "reply to" address of the message.
     *
     * @param  array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function replyTo($address, $name = null)
    {
        if ($this->arrayOfAddresses($address)) {
            $this->replyTo += $this->parseAddresses($address);
        } else {
            $this->replyTo[] = [$address, $name];
        }

        return $this;
    }

    /**
     * Set the cc address for the mail message.
     *
     * @param  array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function cc($address, $name = null)
    {
        if ($this->arrayOfAddresses($address)) {
            $this->cc += $this->parseAddresses($address);
        } else {
            $this->cc[] = [$address, $name];
        }

        return $this;
    }

    /**
     * Set the bcc address for the mail message.
     *
     * @param  array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function bcc($address, $name = null)
    {
        if ($this->arrayOfAddresses($address)) {
            $this->bcc += $this->parseAddresses($address);
        } else {
            $this->bcc[] = [$address, $name];
        }

        return $this;
    }

    /**
     * Attach a file to the message.
     *
     * @param  string  $file
     * @param  array  $options
     * @return $this
     */
    public function attach($file, array $options = [])
    {
        $this->attachments[] = compact('file', 'options');

        return $this;
    }

    /**
     * Attach in-memory data as an attachment.
     *
     * @param  string  $data
     * @param  string  $name
     * @param  array  $options
     * @return $this
     */
    public function attachData($data, $name, array $options = [])
    {
        $this->rawAttachments[] = compact('data', 'name', 'options');

        return $this;
    }

    /**
     * Set the priority of this message.
     *
     * The value is an integer where 1 is the highest priority and 5 is the lowest.
     *
     * @param  int  $level
     * @return $this
     */
    public function priority($level)
    {
        $this->priority = $level;

        return $this;
    }

    /**
     * Get the data array for the mail message.
     *
     * @return array
     */
    public function data()
    {
        return array_merge($this->toArray(), $this->viewData);
    }

    /**
     * Parse the multi-address array into the necessary format.
     *
     * @param  array  $value
     * @return array
     */
    protected function parseAddresses($value)
    {
        return collect($value)->map(function ($address, $name) {
            return [$address, is_numeric($name) ? null : $name];
        })->values()->all();
    }

    /**
     * Determine if the given "address" is actually an array of addresses.
     *
     * @param  mixed  $address
     * @return bool
     */
    protected function arrayOfAddresses($address)
    {
        return is_array($address) ||
            $address instanceof Arrayable ||
            $address instanceof Traversable;
    }

    /**
     * Render the mail notification message into an HTML string.
     *
     * @return string
     */
    public function render()
    {
        if (isset($this->view)) {
            return Container::getInstance()->make('mailer')->render(
                $this->view, $this->data()
            );
        }

        return Container::getInstance()
            ->make(Markdown::class)
            ->render($this->markdown, $this->data());
    }

    /**
     * Register a callback to be called with the Swift message instance.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function withSwiftMessage($callback)
    {
        $this->callbacks[] = $callback;

        return $this;
    }


    /**
     * Add a line of text to the notification.
     *
     * @param  array  $table
     * @return $this
     */
    public function table($table = [])
    {

        if(array_key_exists('header', $table) && array_key_exists('body', $table)){
            $table_headers = $table['header'];
            $table_rows = $table['body'];



            $table_max_length = 0;
            foreach ($table_headers as $table_header){
                $len = strlen($table_header);
                if($table_max_length < $len){
                    $table_max_length = $len;
                }
            }
            foreach ($table_rows as $table_row){
                foreach ($table_row as $table_row_item) {
                    $len = strlen($table_row_item);
                    if ($table_max_length < $len) {
                        $table_max_length = $len;
                    }
                }
            }

            $table_header_data = '';
            $table_header_line = '';
            foreach ($table_headers as $table_header){
                $table_header_data .= '| ' . str_pad($table_header, $table_max_length, ' ', STR_PAD_RIGHT). ' ';

                if($table_headers[0] === $table_header) {
                    $table_header_line .= '|:' . str_pad('', $table_max_length, '-', STR_PAD_RIGHT) . ' ';
                }else if($table_headers[count($table_headers) -1] === $table_header){
                    $table_header_data .= '|';
                    $table_header_line .= '| ' . str_pad('', $table_max_length, '-', STR_PAD_RIGHT) . ':';
                }else{
                    $table_header_line .= '|:' . str_pad('', $table_max_length, '-', STR_PAD_RIGHT) . ':';
                }
            }

            $table_data[] = $table_header_data;
            $table_data[] = $table_header_line;

            foreach ($table_rows as $table_row){
                $table_row = array_values($table_row);
                $table_body_data = '';
                foreach ($table_row as $table_row_item) {
                    $table_body_data .= '| ' . str_pad($table_row_item, $table_max_length, ' ', STR_PAD_RIGHT). ' ';
                    if($table_row[(count($table_row) -1)] === $table_row_item){
                        $table_body_data .= '|';
                    }
                }
                $table_data[] = $table_body_data;
            }
            $this->table = $table_data;
        }
        return $this;
    }

    /**
     * Get an array representation of the message.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'level' => $this->level,
            'subject' => $this->subject,
            'greeting' => $this->greeting,
            'salutation' => $this->salutation,
            'introLines' => $this->introLines,
            'outroLines' => $this->outroLines,
            'actionText' => $this->actionText,
            'actionUrl' => $this->actionUrl,
            'table' => $this->table,
            'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $this->actionUrl),
        ];
    }
}
