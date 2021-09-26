import React, { useState, useEffect } from 'react';
import Loader from './Loader/Loader';//картинка загрузки
import Table from './Table/Table';
import TableNew from './Table/TableNew';
import _ from 'lodash';//для сортировки таблицы
import { searchZamer, average_lenght } from './Logic/logic.js';
// сначала npm install anychart-react
import AnyChart from 'anychart-react'
//npm i @vkontakte/vkui @vkontakte/icons @vkontakte/vk-bridge
import { View, Panel, PanelHeader, Search } from '@vkontakte/vkui';//пакеты из вк
import '@vkontakte/vkui/dist/vkui.css';
import FreeSolo from './Autocomplete/FreeSolo';
import {Context} from './context'


// https://abcinblog.blogspot.com/2019/02/react-i.html сделано по урокам

export default function App() {
  const [state, setState] = useState({
    isLoading: false,
    dataArr: [],
    data: [],
    sort: 'asc',  // 'desc'
    sortField: 'id', // поле по умолчанию
    row: null,
    sortZamer: '',
    data_new: [],
    query: '',
    graf: false
  })

  const componentDidMount_emulator = () => {
    console.log(state)
    const lastState = localStorage.state
    if (lastState) {
      // console.log(lastState)
      setState(JSON.parse(localStorage.state))
    }
  }

  // с помочью хуков эмулирую componentDidMount для загрузки стейта из хранилища 
  useEffect(componentDidMount_emulator, [])

  // следим за стейтом и сохраняем его в хранилище, больше не надо самим сохранять в хранилище
  useEffect(() => {
    localStorage.state = JSON.stringify(state);//сохраняем стейт в локалсторадже
  }, [state])

  // запрос к серверу за данными
  const requestData = async () => {
    if (state.query.length > 2) {
    //пока грузится показываем спинер
      setState({
        ...state,
        isLoading: true
      })
      const response = await fetch(`https://ilgiz.h1n.ru/from_sql_json.php?&query=${state.query}`)
      const data = await response.json()
      console.log(data)
      setState({
        ...state,
        isLoading: false,
        dataArr: data.pov_info,
        data: searchZamer(data.pov_info),
        data_new: data.pov_new
      })
    }
  }

  // здесь используем библиотеку lodash для сортировки таблицы
  const onSort = sortField => {
    const cloneData = state.data.concat();
    const sortType = state.sort === 'asc' ? 'desc' : 'asc';
    const orderedData = _.orderBy(cloneData, sortField, sortType);
    setState({
      ...state,
      data: orderedData,
      sort: sortType,
      sortField,
    })
  }

  const _onChange = (event) => {
    const orderedData = searchZamer(state.dataArr, event.target.value)
    setState({
      ...state,
      data: orderedData
    })
  }

  //выбираем сортировку от пс рп тп
  const handleChange = (event) => {
    setState({
      ...state,
      query: event.target.value
    })
  }

  // data_grapf = [
  //   ["тп-301 600м тп-3002"],
  //   ["тп-301 500м тп-3002"]
  // ];
  const data_grapf = () => {
    const data = state.data
    let res_arr = []
    for (let elm of data) {
      if ((elm.zamer > 0) && (elm.zamer < 10000)) {
        const name = elm.name.split(' - ')
        const elem_arr = [`${name[0].split(' ').pop()} ${elm.zamer}m ${name[1]}`]
        res_arr.push(elem_arr)
      }
    }
    // console.log(res_arr)
    return res_arr
  }


  return (
    <Context.Provider value={handleChange}>
    <View activePanel="main">
      <Panel id="main">
        <PanelHeader>поиск КЛ</PanelHeader>
        <div className="container bg-dark text-center text-white">
          <div className='container p-2'>
            <a type="button" className="btn btn-danger btn-lg btn-block" href='https://ilgiz.h1n.ru/index.php'>на главную</a>
            <Search value={state.query} onChange={handleChange} placeholder='введите КЛ, не менее 3 символов' />
            <FreeSolo onChange={handleChange} />
            <button className='btn btn-info btn-lg btn-block' onClick={requestData}>поиск</button>
            <select className="form-control"
              onChange={_onChange}>
              <option value="">по замеру</option>
              <option value="РП">замер от РП</option>
              <option value="ПС">замер от ПС</option>
              <option value="ТП">замер от ТП</option>
            </select>
          </div>

          {state.data.length ?//здесь выводим граф если есть данные
            <div>
              {state.graf ?//проверяем видимость
                <>
                  <button className='btn btn-secondary btn-lg btn-block' onClick={() => setState({ ...state, graf: false })}>закрыть граф замеров</button>
                  <AnyChart
                    // type='column'
                    // type='bar'
                    // width= '800'
                    height={data_grapf().length * 15 > 800 ? 800 : data_grapf().length * 25}//выбираем высоту графика исходя из данных
                    type='wordtree'
                    // data={[[1, 2], [3, 5]]}
                    // из стейта берем замеры в массив
                    // data={this.state.data.map((elm) => (elm.zamer > 0 && elm.zamer < 10000) ? elm.zamer : null)}
                    data={data_grapf()}
                  // title="замеры до повреждения"
                  />
                </> : <button className='btn btn-secondary btn-lg btn-block' onClick={() => setState({ ...state, graf: true })}>показать граф замеров</button>
              }
            </div>
            : null
          }

          {state.isLoading ?
            <Loader /> :
            <div>
              {state.data.length ?
                <Table
                  data={state.data}
                  onSort={onSort}
                  sort={state.sort}
                  sortField={state.sortField}
                  lenght={average_lenght(state.data)}
                /> :
                <p>в основной базе не ничего не найдено :(</p>}
              {state.data_new.length ?
                <TableNew
                  data={state.data_new}
                /> :
                <p>в новой базе не ничего не найдено :(</p>}
            </div>
          }
        </div>
      </Panel>
    </View>
    </Context.Provider>
  );
}




