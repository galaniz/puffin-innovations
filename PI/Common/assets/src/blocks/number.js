/**
 * Number block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  TextControl
} = window.wp.components

const { InspectorControls } = window.wp.blockEditor
const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'number'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Block */

registerBlockType(name, {
  title: 'Number',
  category: 'theme-blocks',
  icon: 'marker',
  attributes: attr,
  edit (props) {
    const { attributes, setAttributes } = props
    const { number = def.number } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Number Options'>
            <TextControl
              label='Number'
              value={number}
              type='number'
              onChange={number => setAttributes({ number })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Number' initialOpen={false}>
          <div className='t-h2'>
            {number}
          </div>
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return null // Rendered in php
  }
})
